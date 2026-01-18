<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if books table exists, if not create it
        $tables = DB::select("SHOW TABLES LIKE 'books'");

        if (empty($tables)) {
            // Create books table manually
            DB::statement("
                CREATE TABLE books (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(255) NOT NULL,
                    author VARCHAR(255) NOT NULL,
                    isbn VARCHAR(255) NOT NULL UNIQUE,
                    genre VARCHAR(100) NOT NULL,
                    description TEXT,
                    price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
                    stock INT NOT NULL DEFAULT 0,
                    is_free TINYINT(1) NOT NULL DEFAULT 0,
                    published_year INT NOT NULL,
                    cover_image VARCHAR(255) NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }

        // Insert sample books using query builder to avoid quote issues
        $books = [
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'isbn' => '9786020342444',
                'genre' => 'Fiction',
                'description' => 'Novel tentang perjuangan cinta dan persahabatan.',
                'price' => 85000,
                'stock' => 15,
                'is_free' => 0,
                'published_year' => 2008,
                'cover_image' => null
            ],
            [
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'isbn' => '9786020342445',
                'genre' => 'Fiction',
                'description' => 'Karya sastra terbesar di Indonesia.',
                'price' => 125000,
                'stock' => 8,
                'is_free' => 0,
                'published_year' => 1980,
                'cover_image' => null
            ],
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'isbn' => '9780735211299',
                'genre' => 'Self-Help',
                'description' => 'Tiny changes, remarkable results.',
                'price' => 150000,
                'stock' => 25,
                'is_free' => 0,
                'published_year' => 2018,
                'cover_image' => null
            ],
            [
                'title' => 'The Psychology of Money',
                'author' => 'Morgan Housel',
                'isbn' => '9780857197689',
                'genre' => 'Business',
                'description' => 'Timeless lessons on wealth, greed, and happiness.',
                'price' => 95000,
                'stock' => 12,
                'is_free' => 0,
                'published_year' => 2020,
                'cover_image' => null
            ],
            [
                'title' => 'Pulang',
                'author' => 'Leila S. Chudori',
                'isbn' => '9786020342450',
                'genre' => 'Fiction',
                'description' => 'Kisah cinta sejati yang tak terlupakan.',
                'price' => 75000,
                'stock' => 20,
                'is_free' => 0,
                'published_year' => 2012,
                'cover_image' => null
            ],
            [
                'title' => 'Filosofi Teras',
                'author' => 'Henry Manampiring',
                'isbn' => '9786020342451',
                'genre' => 'Non-Fiction',
                'description' => 'Buku kumpulan esai tentang kehidupan.',
                'price' => 0,
                'stock' => 100,
                'is_free' => 1,
                'published_year' => 2018,
                'cover_image' => null
            ],
            [
                'title' => 'Negeri 5 Menara',
                'author' => 'Ahmad Fuadi',
                'isbn' => '9786020342452',
                'genre' => 'Fiction',
                'description' => 'Novel petualangan yang mengharukan.',
                'price' => 95000,
                'stock' => 5,
                'is_free' => 0,
                'published_year' => 2019,
                'cover_image' => null
            ],
            [
                'title' => 'Laskar Pelangi 2',
                'author' => 'Andrea Hirata',
                'isbn' => '9786020342453',
                'genre' => 'Fiction',
                'description' => 'Kelanjutan dari Laskar Pelangi.',
                'price' => 90000,
                'stock' => 10,
                'is_free' => 0,
                'published_year' => 2011,
                'cover_image' => null
            ],
            [
                'title' => 'Sapiens',
                'author' => 'Yuval Noah Harari',
                'isbn' => '9780062316097',
                'genre' => 'History',
                'description' => 'A Brief History of Humankind.',
                'price' => 180000,
                'stock' => 18,
                'is_free' => 0,
                'published_year' => 2014,
                'cover_image' => null
            ],
            [
                'title' => 'Gadis Kretek',
                'author' => 'Ratih Kumala',
                'isbn' => '9786020342454',
                'genre' => 'Fiction',
                'description' => 'Novel remaja yang populer.',
                'price' => 80000,
                'stock' => 30,
                'is_free' => 0,
                'published_year' => 2018,
                'cover_image' => null
            ],
        ];

        foreach ($books as $book) {
            DB::table('books')->insert($book);
        }

        echo "Books seeded successfully!\n";
    }
}
