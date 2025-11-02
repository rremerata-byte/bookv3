<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookAction;
use App\Models\LoginHistory;
use App\Models\BookRequest;
use App\Models\Borrowing;
use App\Models\Reservation;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Shared logic for top 5 most request users (borrow/reserve)
    private function getTopRequestUsers($limit = 5)
    {
        return \App\Models\User::select('id', 'fullname')
            ->withCount(['bookRequests as total_requests' => function($query) {
                $query->whereIn('request_type', ['borrow', 'reserve']);
            }])
            ->orderByDesc('total_requests')
            ->limit($limit)
            ->get()
            ->map(fn($user) => [
                'fullname' => $user->fullname,
                'total_requests' => $user->total_requests
            ]);
    }
    public function index()
    {
        $stats = [
            'totalBooks' => Book::count(),
            'totalBorrowings' => Borrowing::whereNull('returned_at')->count(),
            'totalReturns' => BookAction::where('type', 'return')->count(),
            'totalLoginsToday' => LoginHistory::whereDate('created_at', today())->count(),
            // Active borrowings should include current borrowings (not returned)
            // and active approved reservations (until their return_date)
            // Count distinct books that are currently borrowed (not returned)
            'activeBorrowings' => Borrowing::whereNull('returned_at')->distinct()->count('book_id'),
            // Count distinct books that are currently reserved and approved with return_date today or later
            'activeReservations' => Reservation::where('status', 'approved')->whereDate('return_date', '>=', today())->distinct()->count('book_id'),
            'pendingRequests' => BookRequest::where('status', 'pending')->count(),
        ];


        $loginsData = LoginHistory::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Prepare monthly borrow and reservation counts for the current year (Jan-Dec)
        $year = now()->year;

        $borrowCountsRaw = Borrowing::selectRaw('MONTH(borrowed_date) as month, COUNT(*) as count')
            ->whereYear('borrowed_date', $year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $reserveCountsRaw = Reservation::selectRaw('MONTH(reservation_date) as month, COUNT(*) as count')
            ->whereYear('reservation_date', $year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill arrays for 12 months (1..12)
        $borrowMonthly = [];
        $reserveMonthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $borrowMonthly[] = isset($borrowCountsRaw[$m]) ? (int)$borrowCountsRaw[$m] : 0;
            $reserveMonthly[] = isset($reserveCountsRaw[$m]) ? (int)$reserveCountsRaw[$m] : 0;
        }

        $monthLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        // Yearly aggregation for the last 5 years (including current year)
        $currentYear = now()->year;
        $startYear = $currentYear - 4;

        $startDate = \Carbon\Carbon::create($startYear, 1, 1)->startOfDay();
        $endDate = \Carbon\Carbon::create($currentYear, 12, 31)->endOfDay();

        $borrowYearRaw = Borrowing::selectRaw('YEAR(borrowed_date) as year, COUNT(*) as count')
            ->whereBetween('borrowed_date', [$startDate, $endDate])
            ->groupBy('year')
            ->pluck('count', 'year')
            ->toArray();

        $reserveYearRaw = Reservation::selectRaw('YEAR(reservation_date) as year, COUNT(*) as count')
            ->whereBetween('reservation_date', [$startDate, $endDate])
            ->groupBy('year')
            ->pluck('count', 'year')
            ->toArray();

        $yearLabels = [];
        $borrowYearly = [];
        $reserveYearly = [];
        for ($y = $startYear; $y <= $currentYear; $y++) {
            $yearLabels[] = (string)$y;
            $borrowYearly[] = isset($borrowYearRaw[$y]) ? (int)$borrowYearRaw[$y] : 0;
            $reserveYearly[] = isset($reserveYearRaw[$y]) ? (int)$reserveYearRaw[$y] : 0;
        }

        $topBooks = Book::withCount(['borrowings'])
            ->orderByDesc('borrowings_count')
            ->limit(5)
            ->get()
            ->map(fn($book) => [
                'title' => $book->title,
                'count' => $book->borrowings_count
            ]);

        // Top 5 users with most borrow/reserve actions (shared with AvailableBooks)
        $topRequestUsers = $this->getTopRequestUsers(5);

        $recentActions = BookAction::with(['book', 'user'])
            ->latest('action_date')
            ->limit(10)
            ->get()
            ->map(fn($action) => [
                'id' => $action->id,
                'book' => [
                    'title' => $action->book->title
                ],
                'user' => [
                    'fullname' => $action->user->fullname
                ],
                'type' => $action->type,
                'date' => $action->action_date
            ]);

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'loginsData' => $loginsData,
            'borrowMonthly' => $borrowMonthly,
            'reserveMonthly' => $reserveMonthly,
            'monthLabels' => $monthLabels,
            'borrowYearly' => $borrowYearly,
            'reserveYearly' => $reserveYearly,
            'yearLabels' => $yearLabels,
            'topBooks' => $topBooks,
            'topRequestUsers' => $topRequestUsers,
            'bookActions' => $recentActions,
        ]);
    }


    public function studentList()
    {
        $students = User::all();
        return Inertia::render('Student/StudentList', [
            'students' => $students
        ]);
    }

    public function storeStudent(Request $request)
{
    $validated = $request->validate([
        'fullname' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'studentid' => 'required|string|unique:users',
        'courseSection' => 'required|string',
        'gender' => 'required|in:Male,Female,Other',
        'password' => ['required', 'confirmed'],
        'phone_number' => 'nullable|string|max:20',
    ]);

    $user = User::create([
        'fullname' => $validated['fullname'],
        'email' => $validated['email'],
        'studentid' => $validated['studentid'],
        'courseSection' => $validated['courseSection'],
        'gender' => $validated['gender'],
        'password' => Hash::make($validated['password']),
        'phone_number' => $validated['phone_number'],
        'role' => 'user',
    ]);

    return redirect()->back()->with('message', 'Student created successfully');
}

    public function updateStudent(Request $request, User $user)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'studentid' => 'required|string|unique:users,studentid,'.$user->id,
            'courseSection' => 'required|string',
            'gender' => 'required|in:Male,Female,Other',
            'phone_number' => 'nullable|string|max:20',
            'current_password' => 'required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
        }

        $updateData = [
            'fullname' => $validated['fullname'],
            'email' => $validated['email'],
            'studentid' => $validated['studentid'],
            'courseSection' => $validated['courseSection'],
            'gender' => $validated['gender'],
            'phone_number' => $validated['phone_number'],
        ];

        if ($request->filled('new_password')) {
            $updateData['password'] = Hash::make($validated['new_password']);
        }

        $user->update($updateData);

        return redirect()->back()->with('message', 'Student updated successfully');
    }

    public function deleteStudent(User $user)
    {
        $user->delete();
        return redirect()->back()->with('message', 'Student deleted successfully');
    }
}