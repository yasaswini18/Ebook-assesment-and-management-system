<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Top Books Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Top Rated Books</h3>
                    
                    @if($topBooks->isEmpty())
                        <div class="text-center py-4">
                            <p>No book ratings available yet. Please create some evaluations first.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Book
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Average Score
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Evaluations
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    @foreach($topBooks as $book)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $book->title }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <span class="text-lg font-semibold mr-2">{{ number_format($book->average_score, 1) }}</span>
                                                    <div class="w-24 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ ($book->average_score / 5) * 100 }}%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $book->evaluation_count }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('reports.book', $book->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">View Report</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Criteria Averages Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Criteria Performance</h3>
                    
                    @if($criteriaAverages->isEmpty())
                        <div class="text-center py-4">
                            <p>No criteria ratings available yet. Please create some evaluations first.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Criteria
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Average Score
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    @foreach($criteriaAverages as $criteria)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $criteria->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <span class="text-lg font-semibold mr-2">{{ number_format($criteria->average_score, 1) }}</span>
                                                    <div class="w-24 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                                        <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ ($criteria->average_score / 5) * 100 }}%"></div>
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
            
            <!-- Export Reports Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Export Reports</h3>
                    
                    <form action="{{ route('reports.export', ['type' => 'pdf']) }}" method="GET" class="mb-4">
                        <div class="mb-4">
                            <label for="report_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Report Type</label>
                            <select id="report_type" name="report_type" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm w-full">
                                <option value="all_books">All Books Summary</option>
                                <option value="single_book">Single Book Detailed Report</option>
                            </select>
                        </div>
                        
                        <div id="book_selection" class="mb-4 hidden">
                            <label for="book_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Book</label>
                            <select id="book_id" name="book_id" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm w-full">
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex space-x-4">
                            <button type="submit" formaction="{{ route('reports.export', ['type' => 'pdf']) }}" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                Export as PDF
                            </button>
                            <button type="submit" formaction="{{ route('reports.export', ['type' => 'excel']) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Export as Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reportTypeSelect = document.getElementById('report_type');
            const bookSelection = document.getElementById('book_selection');
            
            reportTypeSelect.addEventListener('change', function() {
                if (this.value === 'single_book') {
                    bookSelection.classList.remove('hidden');
                } else {
                    bookSelection.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>
