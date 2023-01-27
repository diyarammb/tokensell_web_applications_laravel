<?php


namespace App\Http\Controllers\User;

use App\Enums\TransactionType;
use App\Enums\TransactionStatus;
use App\Enums\PaymentMethodStatus;

use App\Models\Transaction;
use App\Models\PaymentMethod;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
class UserDashboardController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::where('status', PaymentMethodStatus::ACTIVE)
            ->get()->keyBy('slug')->toArray();

        $recentTransactions = Transaction::with(['ledger'])
            ->whereIn('status', [TransactionStatus::ONHOLD, TransactionStatus::CONFIRMED, TransactionStatus::COMPLETED])
            ->whereNotIn('type', [TransactionType::REFERRAL])
            ->where('user_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->limit(5)->get();
         $pow_query_array = DB::select("SELECT * FROM `proof_of_work` WHERE `date` = 'date(\"Y-m-d\")'");
        if(empty($pow_query)){
            $pow_query2_array = DB::select("SELECT * FROM `proof_of_work` ORDER BY `date` DESC LIMIT 1");
            $pow_query = $pow_query2_array;
        }else{
            $pow_query = $pow_query_array;
        }
        $pow_all_query = array_reverse(DB::select("SELECT * FROM `proof_of_work` ORDER BY `date` DESC LIMIT 30"));
        
        $chart_data = [];
        $chart_label = [];
        $chart_value = [];
        foreach($pow_all_query as $key => $row){
            array_push($chart_value, $row->hashrate);
            $csm_date = date("d/m", strtotime($row->date));
            array_push($chart_label, $csm_date);
        }
        $chart_label_str = "'".join("','", $chart_label)."'";
        $chart_value_str = join(",", $chart_value);
        $chart_data['label'] = $chart_label_str;
        $chart_data['value'] = $chart_value_str;
        return view('user.dashboard', compact('paymentMethods', 'pow_query','chart_data', 'recentTransactions'));
    }
}
