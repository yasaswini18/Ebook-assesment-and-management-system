<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'title' => 'Introduction to Computer Science',
                'author' => 'John Smith',
                'isbn' => '9781234567897',
                'publisher' => 'Academic Press',
                'year' => 2022,
                'type' => 'Textbook',
                'description' => 'A comprehensive introduction to computer science principles.',
            ],
            [
                'title' => 'Advanced Mathematics for Engineers',
                'author' => 'Jane Doe',
                'isbn' => '9789876543210',
                'publisher' => 'Engineering Publications',
                'year' => 2021,
                'type' => 'Textbook',
                'description' => 'Mathematical concepts and applications for engineering students.',
            ],
            [
                'title' => 'The Complete Guide to Biology',
                'author' => 'Robert Johnson',
                'isbn' => '9785432167890',
                'publisher' => 'Science Books',
                'year' => 2023,
                'type' => 'Reference',
                'description' => 'An in-depth reference guide to biological concepts and theories.',
            ],
            [
                'title' => 'Modern Physics Explained',
                'author' => 'Emily Chen',
                'isbn' => '9780123456789',
                'publisher' => 'Physics Press',
                'year' => 2020,
                'type' => 'E-book',
                'description' => 'A digital guide to modern physics concepts and theories.',
            ],
            [
                'title' => 'Introduction to Psychology',
                'author' => 'Michael Brown',
                'isbn' => '9786789054321',
                'publisher' => 'Behavioral Science Publications',
                'year' => 2022,
                'type' => 'Textbook',
                'description' => 'A foundational textbook for psychology students.',
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
