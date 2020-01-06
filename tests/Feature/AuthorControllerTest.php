<?php

namespace Tests\Feature;

use App\Author;
use App\Book;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorControllerTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    /** @test */
    public function can_reach_home_page_and_shows_the_expected_elements()
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('<h2>Welcome to Authors Society</h2>')
            ->assertSee('<a href="http://localhost/authors" class="btn btn-primary mb-2">Show Data</a>')
            ->assertSee('<a href="http://localhost/authors/create" class="btn btn-success mb-2">Add New</a>');
    }

    /** @test */
    public function can_read_all_data()
    {
        //Create new author and book
        $author = factory('App\Author')->create();
        $book = factory('App\Book')->create();

        //When user visit the authors page
        $response = $this->get('/authors')
            ->assertStatus(200);

        //He should be able to read the author and book data
        $response->assertSee($author->name);
        $response->assertSee($author->birth_date->format('Y-m-d'));
        $response->assertSee($author->address);
        $response->assertSee($book->title);
        $response->assertSee($book->release_date->format('Y-m-d'));
    }

    /** @test */
    public function can_reach_create_page_and_shows_the_expected_elements()
    {
        $this->get('/authors/create')
            ->assertStatus(200)
            ->assertSee('<h2 class="text-center">Add a new author together with a new book</h2>');
    }

    /** @test */
    public function can_create_a_new_author_together_with_a_new_book()
    {
        $response = $this->post('/authors', [
            'name' => 'Chuck Palahniuk',
            'birth_date' => '1962-02-21',
            'address' => '763 Glen Pine Hesselborough, SD 76281',
            'title' => 'Fight Club',
            'release_date' => '1996-08-17'
        ]);

        $response->assertRedirect();

        $authors = Author::all();
        $books = Book::all();

        $this->assertCount(1, $authors);
        $this->assertCount(1, $books);

        $this->assertEquals($authors->first()->name, 'Chuck Palahniuk');
        $this->assertEquals($authors->first()->birth_date, '1962-02-21');
        $this->assertEquals($authors->first()->address, '763 Glen Pine Hesselborough, SD 76281');
        $this->assertEquals($books->first()->title, 'Fight Club');
        $this->assertEquals($books->first()->release_date, '1996-08-17');
    }

    /** @test */
    public function author_name_is_required()
    {

        $response = $this->post('/authors', [
            'title' => '',
            'release_date' => '1996-08-17',
            'name' => '',
            'birth_date' => '1962-02-21',
            'address' => '763 Glen Pine Hesselborough, SD 76281'
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertEquals(session('errors')
            ->get('name')[0], 'The name field is required.');
    }

    /** @test */
    public function author_birth_date_is_required()
    {

        $response = $this->post('/authors', [
            'title' => '',
            'release_date' => '1996-08-17',
            'name' => 'Chuck Palahniuk',
            'birth_date' => '',
            'address' => '763 Glen Pine Hesselborough, SD 76281'
        ]);

        $response->assertSessionHasErrors('birth_date');
        $this->assertEquals(session('errors')
            ->get('birth_date')[0], 'The birth date field is required.');
    }

    /** @test */
    public function book_title_is_required()
    {

        $response = $this->post('/authors', [
            'title' => '',
            'release_date' => '1996-08-17',
            'name' => 'Chuck Palahniuk',
            'birth_date' => '1962-02-21',
            'address' => '763 Glen Pine Hesselborough, SD 76281'
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertEquals(session('errors')
            ->get('title')[0], 'The title field is required.');
    }

    /** @test */
    public function book_release_date_is_required()
    {
        $response = $this->post('/authors', [
            'title' => 'Fight Club',
            'release_date' => '',
            'name' => 'Chuck Palahniuk',
            'birth_date' => '1962-02-21',
            'address' => '763 Glen Pine Hesselborough, SD 76281'
        ]);

        $response->assertSessionHasErrors('release_date');
        $this->assertEquals(session('errors')
            ->get('release_date')[0], 'The release date field is required.');
    }

    /** @test */
    public function book_title_is_unique()
    {
        $this->post('/authors', [
            'title' => 'Fight Club',
            'release_date' => '1996-08-17',
            'name' => 'Chuck Palahniuk',
            'birth_date' => '1962-02-21',
            'address' => '763 Glen Pine Hesselborough, SD 76281'
        ]);

        $response = $this->post('/authors', [
            'title' => 'Fight Club',
            'release_date' => '2001-03-19',
            'name' => 'Stephen King',
            'birth_date' => '1947-09-21',
            'address' => '930 Kemmer Stravenue Apt. 511 Langworthbury, MI 88480'
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertEquals(session('errors')
            ->get('title')[0], 'The title has already been taken.');
    }

    /** @test */
    public function birth_date_can_not_be_future_date()
    {
        $response = $this->post('/authors', [
            'title' => 'Fight Club',
            'release_date' => '2131-08-17',
            'name' => 'Chuck Palahniuk',
            'birth_date' => '2120-01-03',
            'address' => '763 Glen Pine Hesselborough, SD 76281'
        ]);

        $response->assertSessionHasErrors('birth_date');
        $this->assertEquals(session('errors')
            ->get('birth_date')[0], 'The birth date must be a date before now.');
    }

    /** @test */
    public function release_date_min_5_years_later_than_birth_date()
    {
        $response = $this->post('/authors', [
            'title' => 'Fight Club',
            'release_date' => '1949-08-17',
            'name' => 'Chuck Palahniuk',
            'birth_date' => '1947-09-21',
            'address' => '763 Glen Pine Hesselborough, SD 76281'
        ]);

        $response->assertSessionHasErrors('release_date');
        $this->assertEquals(session('errors')
            ->get('release_date')[0], 'The release date must be a date after or equal to 1952-09-21.');
    }

    /** @test */
    public function name_is_max_50_char()
    {
        $response = $this->post('/authors', [
            'title' => 'Fight Club',
            'release_date' => '1996-08-17',
            'name' => 'Maria Fernanda Sara Celia Marina Delgado Rodriguez Lima',
            'birth_date' => '1962-02-21',
            'address' => '763 Glen Pine Hesselborough, SD 76281'
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertEquals(session('errors')
            ->get('name')[0], 'The name may not be greater than 50 characters.');
    }

    /** @test */
    public function address_is_max_255_char()
    {
        $response = $this->post('/authors', [
            'title' => 'Fight Club',
            'release_date' => '1996-08-17',
            'name' => 'Chuck Palahniuk',
            'birth_date' => '1962-02-21',
            'address' => 'kBWXdCTxHjMDymoEuYhqHerbN1ElnrxQSs5ywP62ZHIzzDLQfg0IIa4wyAKllrFk
                          e8ncBYSYPHsgzb9N2WXopXAvQq9Wrd8aQIYHWa0BaM7nCr4fLzIJeATujFYMg9PW
                          pRPwfzIYSI13AFnxkikws1awuxGU2m9oNGnc6k0ehEy9XPOQyU3SR4cFqqIb1JZl
                          3IZZHpy50y0gLPjA1W8TSbZuBc83lMzdVsLYeylV7RQG5jbZdWwyCbd4RdoWNCRN'
        ]);

        $response->assertSessionHasErrors('address');
        $this->assertEquals(session('errors')
            ->get('address')[0], 'The address may not be greater than 255 characters.');
    }

    /** @test */
    public function title_is_max_100_char()
    {
        $response = $this->post('/authors', [
            'title' => 'HelloWorld HelloWorld HelloWorld HelloWorld HelloWorld
                        HelloWorld HelloWorld HelloWorld HelloWorld HelloWorld',
            'release_date' => '1996-08-17',
            'name' => 'Chuck Palahniuk',
            'birth_date' => '1962-02-21',
            'address' => '763 Glen Pine Hesselborough, SD 76281'
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertEquals(session('errors')
            ->get('title')[0], 'The title may not be greater than 100 characters.');
    }

    /** @test */
    public function address_is_nullable()
    {
        $response = $this->post('/authors', [
            'title' => 'Fight Club',
            'release_date' => '1996-08-17',
            'name' => 'Chuck Palahniuk',
            'birth_date' => '1962-02-21',
            'address' => null
        ]);

        $response->assertSessionHasNoErrors();
    }
}
