<?php

namespace Tests\Feature;

use App\Models\Guru;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class GuruTest extends TestCase {

    /**
     * Get token.
     *
     * @return void
     */
    public function getToken() {
        return JWTAuth::fromUser(Guru::first());
    }

    /**
     * Login test.
     *
     * @return void
     */
    public function testLogin() {
        $response = $this->post('/api/auth/login', [
            'email' => 'fatur@hadirkuy.test',            
            'password' => 'fatur12345',            
        ]);

        $response->assertStatus(200);
    }

    /**
     * Logout test.
     *
     * @return void
     */
    public function testLogout() {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/api/auth/logout');

        $response->assertStatus(200);
    }

    /**
     * Get guru data.
     *
     * @return void
     */
    public function testGetUser() {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->get('/api/auth/user');
        $response->assertStatus(200);
    }

    /**
     * Get statistic.
     *
     * @return void
     */
    public function testGetStatistic() {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->get('/api/statistic');
        $response->assertStatus(200);
    }

    /**
     * Get all siswa.
     *
     * @return void
     */
    public function testGetAllSiswa() {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->get('/api/siswa');
        $response->assertStatus(200);
    }

    /**
     * Get siswa by id.
     *
     * @return void
     */
    public function testGetSiswa() {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->get('/api/siswa/1');
        $response->assertStatus(200);
    }

    /**
     * Create pertemuan
     *
     * @return void
     */
    public function testCreatePertemuan() {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->post('/api/pertemuan',
        [
            'nama' => 'Pertemuan Test',
            'tanggal' => '2021-05-28',
            'waktu' => '14:00'
        ]);
        $response->assertStatus(200);
    }

    /**
     * Get all pertemuan.
     *
     * @return void
     */
    public function testGetAllPertemuan() {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->get('/api/pertemuan');
        $response->assertStatus(200);
    }
    /**
     * Get pertemuan by id
     *
     * @return void
     */
    public function testGetPertemuan() {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken()
        ])->get('/api/pertemuan/detail/1');
        $response->assertStatus(200);
    }

}
