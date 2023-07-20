<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderCollection;
use App\Models\Order;
use App\Models\Inversion;
use App\Models\Ticket;
use App\Models\PackageMembership;
use App\Models\User;
use App\Models\WalletComission;
use App\Repositories\OrderRepository;
use App\Repositories\TicketRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
	protected $ticketRepository;
	protected $orderRepository;

	public function __construct(TicketRepository $ticketRepository, OrderRepository $orderRepository)
	{
		$this->ticketRepository = $ticketRepository;
		$this->orderRepository = $orderRepository;
	}

	public function getLast10SupportTickets()
	{
		$tickets = $this->ticketRepository->getTicketsByQuantity(10);

		$data = [];

		foreach ($tickets as $ticket) {
			$data[] = [
				'id' => $ticket->id,
				'user_id' => $ticket->user_id,
				'status' => $ticket->status,
				'subject' => $ticket->subject,
				'categories' => $ticket->categories,
				'user' => [
					'name' => ucwords(strtolower($ticket->user->name)),
					'last_name' => ucwords(strtolower($ticket->user->last_name)),
					'user_name' => strtolower($ticket->user->user_name),
					'email' => strtolower($ticket->user->email),
					'profile_picture' => $ticket->user->profile_picture,
				]
			];
		}

		return response()->json($data, 200);
	}
	public function getTicketsAdmin()
	{
		$tickets = Ticket::where('status', 0)->with('user')->with('messages')->orderBy('updated_at', 'desc')->take(10)->get();
		return response()->json($tickets, 200);
	}

	public function mostRequestedPackages()
	{
		$evaluations = Order::whereHas('packageMembership', function ($q) {
			$q->where('type', 1);
		})->count();


		$fast = Order::whereHas('packageMembership', function ($q) {
			$q->where('type', 2);
		})->count();

		$accelerated = Order::whereHas('packageMembership', function ($q) {
			$q->where('type', 2);
		})->count();

		$one_hundred = $evaluations + $fast + $accelerated;

		$data = [
			'evaluations' => $this->calculatePercent($one_hundred, $evaluations),
			'fast' => $this->calculatePercent($one_hundred, $fast),
			'accelerated' => $this->calculatePercent($one_hundred, $accelerated)
		];

		return response()->json($data, 200);
	}

	private function calculatePercent(int $one_hundred, int $amount)
	{
		return ($amount * 100) / $one_hundred;
	}

	public function getLast10Orders()
	{
		$orders = $this->orderRepository->getOrdersByQuantity(10);

		$data = [];
		foreach ($orders as $order) {
			if (isset($order->project)) {
				$phase = ($order->project->phase2 == null && $order->project->phase1 == null)
					? ''
					: (($order->project->phase2 == null)
						? 'Phase 1'
						: 'Phase 2');
			}

			$data[] = [
				'id' => $order->id,
				'date' => $order->created_at,
				'status' => $order->status,
				'program' => [
					'name' => $order->packageMembership->getTypeName(),
					'account' => $order->packageMembership->account,
					'phase' => $phase ?? '',
				],
				'user' => [
					'id' => $order->user_id,
					'name' => ucwords(strtolower($order->user->name)),
					'last_name' => ucwords(strtolower($order->user->last_name)),
					'user_name' => strtolower($order->user->user_name),
					'email' => strtolower($order->user->email),
					'profile_picture' => $order->user->profile_picture,
				]
			];
		}
		return response()->json($data, 200);
	}

	public function getOrders()
	{
		$orders = $this->orderRepository->getOrders();

		foreach ($orders as $order) {
			$data[] = [
				'id' => $order->id,
				'user_id' => $order->user->id,
				'user_name' => strtolower(explode(" ", $order->user->name)[0] . " " . explode(" ", $order->user->last_name)[0]),
				'status' => $order->status,
				'description' => $order->packagesB2B->package,
				'hash_id' => $order->hash,
				'amount' => round($order->amount, 2),
				'date' => $order->created_at->format('Y-m-d'),
				'update_date' => $order->updated_at	->format('Y-m-d')
			];
		}
		return response()->json($data, 200);
	}

    public function sumOrderPaid()
    {
        $order = Order::where('status', '1')->get();

        $data = $order->sum('amount');

        return response()->json($data, 200);
    }

    public function sumComissionPaid()
    {
        $order = WalletComission::where('status', '2')->get();

        $data = $order->sum('amount_retired');

        return response()->json($data, 200);
    }

    public function gainWeekly()
    {
        // Obtener los datos de la tabla 'ordenes' ordenados por fecha de creación
        $ordenes = Order::where('status', '1')->orderBy('created_at')->get();

        // Crear un arreglo para almacenar los datos de la gráfica
        $data = [];

        // Iterar sobre los registros de la tabla 'ordenes'
        foreach ($ordenes as $orden) {
            $diaSemana = $orden->created_at->format('D');
            $ganancias = $orden->amount;

            // Agregar los datos al arreglo de la gráfica
            $data[$diaSemana] = $ganancias;
        }

        // Devolver los datos de la gráfica como respuesta JSON
        return response()->json($data, 200);
    }

    public function topFiveUsers()
    {
        $data = DB::table('users')
            ->select('users.*', DB::raw('(SELECT COUNT(*) FROM users u WHERE u.buyer_id = users.id) as total_referidos'))
            ->orderByDesc('total_referidos')
            ->limit(5)
            ->get();

        return response()->json($data, 200);
    }

    public function mountMatrix()
    {

        $matrix = WalletComission::get();
        $matrixTotalAmount = $matrix->sum('amount');
        $totalAmountMatrix20 = $matrix->where('type', '1')->sum('amount');
        $totalAmountMatrix200 = $matrix->where('type', '2')->sum('amount');
        $totalAmountMatrix2000 = $matrix->where('type', '3')->sum('amount');

        $data = array(
            'matrixTotalAmount'     => $matrixTotalAmount,
            'totalAmountMatrix20'   => $totalAmountMatrix20,
            'totalAmountMatrix200'  => $totalAmountMatrix200,
            'totalAmountMatrix2000' => $totalAmountMatrix2000
        );

        return response()->json($data, 200);
    }

    public function totalEarnigs()
    {
        $inversion = PackageMembership::all();
        $data = $inversion->sum('amount');

        return response()->json($data, 200);
    }

	public function countUserForMatrix()
	{
		$inversions = Inversion::get();
        $userCount = $inversions->sum('amount');
        $userMatrix20 = $inversions->where('type', '0')->sum('amount');
        $userMatrix200 = $inversions->where('type', '1')->sum('amount');
        $userMatrix2000 = $inversions->where('type', '2')->sum('amount');

        $data = array(
            'userCount'     => $userCount,
            'userMatrix20'   => $userMatrix20,
            'userMatrix200'  => $userMatrix200,
            'userMatrix2000' => $userMatrix2000
        );

        return response()->json($data, 200);
	}

    public function countOrderAndCommision()
    {
        // Obtener el conteo de órdenes por mes
        $orders = DB::table('orders')
        ->select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
        ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
        ->get();

        // Obtener el conteo de comisiones por mes
        $commissions = DB::table('wallets_commissions')
        ->select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
        ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
        ->get();

        // Combinar los resultados en un objeto JSON
        $data = [
            'orders' => $orders,
            'commissions' => $commissions
        ];

        return response()->json($data);
    }

}
