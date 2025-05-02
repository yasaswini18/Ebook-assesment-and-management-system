<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;

class ReportExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    protected $data;
    protected $title;
    protected $reportType;

    /**
     * Create a new export instance.
     *
     * @param array $data
     * @param string $title
     * @param string $reportType
     */
    public function __construct($data, $title, $reportType)
    {
        $this->data = $data;
        $this->title = $title;
        $this->reportType = $reportType;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        if ($this->reportType === 'single_book') {
            return $this->getSingleBookCollection();
        } else {
            return $this->getAllBooksCollection();
        }
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        if ($this->reportType === 'single_book') {
            return [
                'Criteria',
                'Average Rating',
            ];
        } else {
            return [
                'Book Title',
                'Author',
                'Publisher',
                'Year',
                'Type',
                'Evaluation Count',
                'Average Rating',
            ];
        }
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * Get collection for single book report.
     *
     * @return Collection
     */
    private function getSingleBookCollection()
    {
        $collection = new Collection();

        foreach ($this->data['criteriaAverages'] as $criteria) {
            $collection->push([
                $criteria->name,
                number_format($criteria->average_score, 2),
            ]);
        }

        // Add overall average
        $collection->push([
            'Overall Average',
            number_format($this->data['overallAverage'], 2),
        ]);

        return $collection;
    }

    /**
     * Get collection for all books report.
     *
     * @return Collection
     */
    private function getAllBooksCollection()
    {
        $collection = new Collection();

        foreach ($this->data['books'] as $book) {
            $collection->push([
                $book->title,
                $book->author,
                $book->publisher,
                $book->year,
                $book->type,
                $book->evaluation_count,
                $book->average_score ? number_format($book->average_score, 2) : 'N/A',
            ]);
        }

        return $collection;
    }
}
