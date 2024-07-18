<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ProductEntry;
use App\Repositories\ProductEntryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductEntryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProductEntryRepository();
    }

    /** @test */
    public function it_can_create_a_product_entry()
    {
        $data = [
            'product_name' => 'Produto Teste',
            'quantity' => 10,
            'unit_price' => 100.00,
            'total_price' => 1000.00,
            'invoice_number' => '123456',
            'invoice_file' => null,
        ];

        $entry = $this->repository->create($data);

        $this->assertInstanceOf(ProductEntry::class, $entry);
        $this->assertEquals('Produto Teste', $entry->product_name);
    }

    /** @test */
    public function it_can_update_a_product_entry()
    {
        $entry = ProductEntry::factory()->create();

        $data = [
            'product_name' => 'Produto Teste Atualizado',
            'quantity' => 20,
            'unit_price' => 200.00,
            'total_price' => 4000.00,
            'invoice_number' => '654321',
            'invoice_file' => null,
        ];

        $updatedEntry = $this->repository->update($entry->id, $data);

        $this->assertInstanceOf(ProductEntry::class, $updatedEntry);
        $this->assertEquals('Produto Teste Atualizado', $updatedEntry->product_name);
    }

    /** @test */
    public function it_can_delete_a_product_entry()
    {
        $entry = ProductEntry::factory()->create();

        $deleted = $this->repository->delete($entry->id);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('product_entries', ['id' => $entry->id]);
    }
}
