<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">Total Books</h3>
                        <p class="text-3xl font-bold">{{ $totalBooks }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">Total Evaluations</h3>
                        <p class="text-3xl font-bold">{{ $totalEvaluations }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">Quick Links</h3>
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('books.index') }}" class="text-blue-600 hover:underline">View Books</a>
                            <a href="{{ route('evaluations.index') }}" class="text-blue-600 hover:underline">View Evaluations</a>
                            <a href="{{ route('reports.index') }}" class="text-blue-600 hover:underline">View Reports</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Evaluations -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Recent Evaluations</h3>

                    @if($recentEvaluations->isEmpty())
                        <p class="text-gray-500">No evaluations yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Evaluator</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentEvaluations as $evaluation)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $evaluation->book->title }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $evaluation->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $evaluation->evaluation_date->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('evaluations.show', $evaluation) }}" class="text-blue-600 hover:underline">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Book Quality Scores -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Book Quality Scores</h3>

                    @if($bookScores->isEmpty())
                        <p class="text-gray-500">No book scores available yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Average Score</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($bookScores as $score)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $score->title }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <span class="text-lg font-semibold mr-2">{{ number_format($score->average_score, 1) }}</span>
                                                    <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ ($score->average_score / 5) * 100 }}%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
