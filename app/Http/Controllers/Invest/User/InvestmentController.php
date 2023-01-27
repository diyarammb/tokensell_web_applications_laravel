<?php

namespace App\Http\Controllers\Invest\User;

use App\Enums\SchemeStatus;
use App\Enums\LedgerTnxType;
use App\Enums\InvestmentStatus;

use App\Models\UserMeta;
use App\Models\IvLedger;
use App\Models\IvProfit;
use App\Models\IvInvest;
use App\Models\IvScheme;

use App\Helpers\MsgState;
use App\Filters\PlansFilter;
use App\Filters\LedgerFilter;

use App\Services\GraphData;
use App\Services\InvestormService;
use App\Services\Investment\IvBalanceTransferService;

use Brick\Math\BigDecimal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class InvestmentController extends Controller
{
    private $investment;

    public function __construct(InvestormService $investment)
    {
        $this->investment = $investment;
        
        
        
        $exchange_rates = get_exchange_rates();
        $csm_query = DB::select("SELECT * FROM `crypto_history` ORDER BY id DESC LIMIT 1;");
        $current_btc_rate = (float)$exchange_rates['automatic']['USD'];
        $db_last_value = (float)$csm_query[0]->value;
        
        
        if(empty($csm_query)){
            $time = substr_replace(time(), "", -1)."0";
            DB::table('crypto_history')->insert([
                'time' => $time,
                'value' => $current_btc_rate
            ]);
        }else{
            if($current_btc_rate !== $db_last_value){
                DB::table('crypto_history')->insert([
                    'time' => time(),
                    'value' => $current_btc_rate
                ]);
            }
        }
        
        
    }

    public function index(Request $request, GraphData $graph)
    {
        $amounts = [];
        $user_id = auth()->id();

        $investments = IvInvest::whereIn('status', [
            InvestmentStatus::PENDING,
            InvestmentStatus::ACTIVE,
            InvestmentStatus::COMPLETED
        ])->where('user_id', $user_id)
            ->orderBy('id', 'desc')->get()->groupBy('status');

        $recents = IvInvest::where('user_id', $user_id)->where('status', InvestmentStatus::COMPLETED)->latest()->limit(3)->get();
        $actived = IvInvest::where('user_id', $user_id)->where('status', InvestmentStatus::ACTIVE)->get();

        // Profit calculate
        if ($actived->count() > 0) {
            $this->bulkCalculate($actived);
        }

        $invested = IvInvest::where('user_id', $user_id)->where('status', InvestmentStatus::ACTIVE);
        $pending = IvInvest::where('user_id', $user_id)->where('status', InvestmentStatus::PENDING);
        $profits = IvProfit::where('user_id', $user_id)->whereNull('payout');

        $amounts['invested'] = $invested->sum('amount');
        $amounts['profit'] = $invested->sum('profit');
        $amounts['locked'] = to_sum($profits->sum('amount'), $pending->sum('amount'));

        // Graph chart
        $graph->set('profit', 'term_start');

        $profit = IvInvest::select(DB::raw('SUM(profit) as profit,term_start'))
                ->where('status', InvestmentStatus::ACTIVE)
                ->groupBy(DB::RAW('CAST(term_start as DATE)'))
                ->get();

        $profitChart = $graph->getDays($profit, 31)->flatten();

        $graph->set('amount', 'term_start');

        $investment = IvInvest::select(DB::raw('SUM(amount) as amount,term_start'))
                    ->where('status', InvestmentStatus::ACTIVE)
                    ->groupBy(DB::RAW('CAST(term_start as DATE)'))
                    ->get();

        $investChart = $graph->getDays($investment, 31)->flatten();


        return view("investment.user.dashboard", compact('investments', 'recents', 'amounts', 'profitChart', 'investChart'));
    }


    public function planList(Request $request)
    {
        $planQuery = IvScheme::query();
        $planOrder = sys_settings('iv_plan_order');

        switch ($planOrder) {
            case "reverse":
                $planQuery->orderBy('id', 'desc');
                break;
            case "random":
                $planQuery->inRandomOrder();
                break;
            case "featured":
                $planQuery->orderBy('featured', 'desc');
                break;
        }

        $plans = $planQuery->where('status', SchemeStatus::ACTIVE)->get();

        if (blank($plans)) {
            $errors = MsgState::of('no-plan', 'invest');
            return view("investment.user.invest.errors", $errors);
        }


        return view("investment.user.invest-plans", compact('plans'));
    }

    public function investmentHistory(Request $request, $status = null)
    {
        $input = array_filter($request->only(['status', 'query']));
        $eligibleStatus = [
            InvestmentStatus::PENDING,
            InvestmentStatus::ACTIVE,
            InvestmentStatus::COMPLETED
        ];

        $investCount = IvInvest::select('id', 'status')->loggedUser()->get()
            ->groupBy('status')->map(function ($item) {
                return count($item);
            });

        $filter = new PlansFilter(new Request(array_merge($input, ['status' => $status ?? $request->get('status')])));
        $investQuery = IvInvest::loggedUser()->orderBy('id', 'desc')->filter($filter);

        if ($status && in_array($status, $eligibleStatus)) {
            $investQuery->where('status', $status);
            $listing = $status;
        } else {
            $listing = 'all';
        }

        $investments = $investQuery->paginate(user_meta('iv_history_perpage', 20))->onEachSide(0);

        return view("investment.user.history", compact('investments', 'investCount', 'listing'));
    }

    public function investmentDetails($id)
    {
        $invest = IvInvest::find(get_hash($id));

        if (blank($invest)) {
            throw ValidationException::withMessages(['invest' => 'Invalid Investment!']);
        }

        $this->profitCalculate($invest);

        $invest = $invest->fresh()->load(['profits' => function ($q) {
            $q->orderBy('term_no', 'asc');
        }]);
    
        $currenciesAll = get_currencies('list');
        $currencies = get_currencies('list', '', true);
        $exchange_methods = exchange_methods();
        $exchange_rates = get_exchange_rates();
        
        
        
        $start_time = strtotime( data_get($invest, 'term_start') );
        $end_time = strtotime( data_get($invest, 'term_end') );
        $term_total = $invest['term_total'];
       
        $csm_investment_history = [];
        
        $amount = $invest->amount + $invest->profit;
        
        $date=date_create(data_get($invest, 'term_start'));
        $current_date=date_create(date("Y-m-d H:i:s"));
        $diff=date_diff($date,$current_date);
        $csm_tm = $diff->h * 60 + $diff->i;
        if($csm_tm == 0){
            $csm_tm = 1;
        }
        $csm_th = $diff->h;
        $csm_td = $diff->d;
        $csm_tdm = $diff->m*30 + $diff->d;
        $csm_total_min = ceil(( time() - $start_time ) / 60);
        
       
        $wd = floor($csm_tdm/7);
        $ongoing_weekly_day = $csm_tdm - ($wd * 7);
        
        
        $md = floor($csm_tdm/28);
        $ongoing_monthly_day = $csm_tdm - ($md * 28);
       
       
        $perminute_total = $amount / ($term_total * 24 * 60);
        
    //   if($invest->status == 'pending' || $invest->status == 'cancelled'){
    //       $invest_id = $invest->id;
    //       $update = DB::table('iv_invests')->where('id', $invest_id)->update([
    //             'date' => $inputs['date'],
    //             'algo' => $inputs['algo'],
    //         ]);
    //   }
       
       
       if($invest->status == 'active' || $invest->status == 'completed'){
           
        $csm_investment_history['ongoing'] = $diff->m*30 + $diff->d. ' days, '. $diff->h. ' hours, ' . $diff->i. ' minutes';
        $csm_investment_history['total'] = number_format($perminute_total * $csm_total_min, 8);
        
        if($csm_total_min < 24 * 60){
           $csm_investment_history['daily'] = number_format($perminute_total * $csm_total_min, 8);
        }else if($csm_total_min > 24 * 60 && $term_total > $csm_tdm){
           $csm_investment_history['daily'] = number_format($perminute_total * $csm_total_min, 8);
        }else{
            $csm_investment_history['daily'] = number_format($perminute_total * (24 * 60), 8);
        }
        
        if($csm_total_min < 24 * 60 * 7){
            $csm_investment_history['weekly'] = number_format($perminute_total * $csm_total_min, 8);
        }else if($csm_total_min > 24 * 60 * 7 && $term_total > $csm_tdm){
            if($ongoing_weekly_day == 0){
                $csm_investment_history['weekly'] = number_format($perminute_total * $csm_tm, 8);
            }else{
                
                $csm_investment_history['weekly'] = number_format($perminute_total * ($ongoing_weekly_day * 60 * 24), 8);
            }
            
        }else{
            $csm_investment_history['weekly'] = number_format($perminute_total * (24 * 60 *7), 8);
        }
        
        
        if($csm_total_min < 24 * 60 * 28){
           $csm_investment_history['monthly'] = number_format($perminute_total * $csm_total_min, 8);
        }else if($csm_total_min > 24 * 60 * 28 && $term_total > $csm_tdm){
            
            
            if($ongoing_monthly_day == 0){
                $csm_investment_history['monthly'] = number_format($perminute_total * $csm_tm, 8);
            }else{
                $csm_investment_history['monthly'] = number_format($perminute_total * ($ongoing_monthly_day * 60 * 24), 8);
            }
            
            
        }else{
           $csm_investment_history['monthly'] = number_format($perminute_total * (24 * 60 *28), 8);
        }
        $csm_investment_history['today'] = number_format($perminute_total * $csm_tm, 8);
        $csm_investment_history['ongoing_day'] = $csm_tdm;
        $csm_investment_history['profit'] = ( $invest->profit / ($term_total * 24 * 60) ) * $csm_total_min;
        $csm_investment_history['total_percentage'] = number_format(((float)$csm_investment_history['total'] / (float)$amount) * 100, 2);
        $csm_investment_history['profit_percentage'] = number_format(((( $invest->profit / ($term_total * 24 * 60) ) * $csm_total_min) / $invest->profit) * 100, 2);
        $csm_investment_history['remaining_days'] = $term_total - $csm_td;
        $csm_investment_history['remaining_days_percentage'] = round(($csm_td / $term_total) * 100, 2); 
       }else{
         
        $csm_investment_history['ongoing'] = '-';
        $csm_investment_history['total'] = '-';
        $csm_investment_history['daily'] = '-';
        $csm_investment_history['weekly'] = '-';
        
        $csm_investment_history['monthly'] = '-';
        
        
        
        $csm_investment_history['today'] = '-';
        $csm_investment_history['ongoing_day'] = '-';
        $csm_investment_history['profit'] = '-';
        $csm_investment_history['total_percentage'] = '-';
        $csm_investment_history['profit_percentage'] = '-';
        $csm_investment_history['remaining_days'] = '-';
        $csm_investment_history['remaining_days_percentage'] = '-'; 
       }
       
       
       
       $csm_query = DB::select("SELECT * FROM `crypto_history` WHERE `time` >= '$start_time' AND `time` <= '$end_time' ORDER BY id DESC LIMIT 10");
       
       
       return view("investment.user.plan", compact("invest", "currencies", "currenciesAll", "exchange_methods", "exchange_rates", "csm_query", "csm_investment_history"));
    }

    public function transactionList(Request $request, $type=null)
    {
        $input = array_filter($request->only(['type', 'source', 'query']));
        $filterCount = count(array_filter($input, function ($item) {
            return !empty($item) && $item !== 'any';
        }));
        $filter = new LedgerFilter(new Request(array_merge($input, ['type' => $type ?? $request->get('type')])));
        $ledgers = get_enums(LedgerTnxType::class, false);
        $sources = [AccType('main'), AccType('invest')];

        $transactions = IvLedger::loggedUser()->with(['invest'])->orderBy('id', 'desc')
            ->filter($filter)
            ->paginate(user_meta('iv_tnx_perpage', 10))->onEachSide(0);

        return view("investment.user.transactions", [
            'transactions' => $transactions,
            'sources' => $sources,
            'ledgers' => $ledgers,
            'type'    => $type ?? 'all',
            'filter_count' => $filterCount,
            'input' => $input,
        ]);
    }

    public function payoutInvest(Request $request)
    {
        $min = min_to_compare();
        $balance = user_balance(AccType('invest'));

        if ($request->ajax()) {
            $balance = (BigDecimal::of($balance)->compareTo($min) != 1) ? false : $balance;

            return view("investment.user.misc.modal-payout", compact("balance"))->render();
        } else {
            return redirect()->route('user.investment.dashboard');
        }
    }

    public function payoutProceed(Request $request)
    {
        $this->validate($request, [
            'amount' => ['required', 'numeric', 'gt:0']
        ], [
            'amount.required' => __('Enter a valid amount to transfer funds.'),
            'amount.numeric' => __('Enter a valid amount to transfer funds.'),
        ]);

        $min = min_to_compare();
        $amount = $request->get('amount');
        $balance = user_balance(AccType('invest'));

        if (BigDecimal::of($balance)->compareTo($min) != 1) {
            throw ValidationException::withMessages([ 'invalid' => __("You do not have enough funds in your account to transfer.") ]);
        }
        if (BigDecimal::of($amount)->compareTo($balance) > 0) {
            throw ValidationException::withMessages(['amount' => ['title' => __('Insufficient balance!'), 'message' => __('The amount exceeds your available funds.')]]);
        }

        if (BigDecimal::of($amount)->compareTo($min) != 1) {
            throw ValidationException::withMessages([ 'invalid' => __("The amount is required to transfer.") ]);
        }

        $transfer = $this->wrapInTransaction(function ($amount) {
            $transferProcessor = new IvBalanceTransferService();
            return $transferProcessor->setUser(auth()->user())->manualTransfer($amount);
        }, $amount);

        if ($transfer) {
            return response()->json(['title' => __('Fund Transfered'), 'msg' => __('Your funds successfully transferred into your main account balance.'), 'reload' => true ]);
        } else {
            throw ValidationException::withMessages(['warning' => __('Sorry, something went wrong! Please reload the page and try again.')]);
        }
    }

    private function profitCalculate(IvInvest $invest)
    {
        if (empty($invest)) {
            return false;
        }

        try {
            if (in_array($invest->status, [InvestmentStatus::ACTIVE, InvestmentStatus::COMPLETED])) {
                $this->wrapInTransaction(function ($invest) {
                    $this->investment->processInvestmentProfit($invest);
                }, $invest);
            }
        } catch (\Exception $e) {
            save_error_log($e, 'profit-calc');
        }

        return true;
    }

    private function bulkCalculate($investments)
    {
        if (empty($investments)) {
            return false;
        }

        foreach ($investments as $invest) {
            $this->profitCalculate($invest);
        }

        return true;
    }

    public function ivSettings(Request $request)
    {
        $user = auth()->user();
        $metas = UserMeta::where('user_id', $user->id)->pluck('meta_value', 'meta_key')->toArray();
        $setting = $user->auto_transfer_settings;

        if ($request->ajax()) {
            return view('investment.user.misc.modal-settings', compact('metas', 'setting'))->render();
        } else {
            return redirect()->route('user.investment.dashboard');
        }
    }

    public function saveIvSettings(Request $request)
    {
        $input = $request->validate([
            'auto_transfer' => 'string',
            'min_transfer' => 'bail|nullable|numeric|gte:'.gss("iv_min_transfer")
        ], [
            'min_transfer.numeric' => __('Minimum amount must be a valid number.'),
            'min_transfer.gte' => __('Minimum amount must be greater than or equal to :amount.', ['amount' => money(gss("iv_min_transfer"), base_currency())]),
        ]);
        $input['auto_transfer'] = $request->get('auto_transfer', 'off');

        foreach ($input as $key => $value) {
            UserMeta::updateOrCreate([
                'user_id' => auth()->user()->id,
                'meta_key' => 'setting_'.$key
            ], ['meta_value' => $value ?? null]);
        }
        
        return response()->json(['title' => __('Settings Updated!'), 'msg' => __('Auto Transfer setting has been updated successfully.'), 'reload' => true]);
    }
}
