<script>
    import { onMount } from 'svelte';
    import axios from 'axios';
  
    let karyawan = [];
    let divisiList = [];
    let selectedDivisi = ""; // Untuk menyimpan divisi yang dipilih
    let total = 0;
    let total_non_aktif = 0;
    let total_aktif = 0;
    let loading = true;
    let error = null;
  
    // Ambil token dari localStorage
    const token = localStorage.getItem('auth_token');
  
    // Fetch data divisi dan karyawan
    const fetchData = async (divisi_id = "") => {
      loading = true;
      error = null;
  
      try {
        const response = await axios.get(`http://localhost:8000/api/karyawan?divisi_id=${divisi_id}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        karyawan = response.data.data;
        total_aktif = response.data.total_aktif;
        total_non_aktif = response.data.total_non_aktif;
        total = total_aktif + total_non_aktif;
      } catch (err) {
        error = err.response?.data?.message || err.message;
      } finally {
        loading = false;
      }
    };
  
    // Fetch daftar divisi
    const fetchDivisi = async () => {
      try {
        const response = await axios.get(`http://localhost:8000/api/divisi`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        divisiList = response.data; // Asumsikan data divisi berbentuk array
      } catch (err) {
        error = "Gagal memuat daftar divisi.";
      }
    };
  
    // Memuat data awal
    onMount(async () => {
      await fetchDivisi();
      await fetchData();
    });
  
    // Saat divisi dipilih
    const handleDivisiChange = async () => {
      await fetchData(selectedDivisi);
    };
  </script>
  
  {#if loading}
    <p>Loading...</p>
  {:else if error}
    <p>Error: {error}</p>
  {:else}
    <div>
      <label for="divisi">Filter berdasarkan divisi:</label>
      <select id="divisi" bind:value={selectedDivisi} on:change={handleDivisiChange}>
        <option value="">Semua Divisi</option>
        {#each divisiList as divisi}
          <option value={divisi.id}>{divisi.nama_divisi}</option>
        {/each}
      </select>
    </div>
  
    <table>
      <thead>
        <tr>
          <th>Nama</th>
          <th>Email</th>
          <th>No Telepon</th>
          <th>Tanggal Lahir</th>
          <th>Divisi</th>
          <th>Pekerjaan</th>
        </tr>
      </thead>
      <tbody>
        {#each karyawan as k}
          <tr>
            <td>{k.nama}</td>
            <td>{k.email}</td>
            <td>{k.no_telepon}</td>
            <td>{new Date(k.tanggal_lahir).toLocaleDateString()}</td>
            <td>
              <a href="#" on:click={() => fetchData(k.divisi.id)}>
                {k.divisi.nama_divisi}
              </a>
            </td>
            <td>{k.pekerjaan.nama_pekerjaan}</td>
          </tr>
        {/each}
      </tbody>
    </table>
    <p>Total Karyawan: {total}</p>
    <p>Aktif: {total_aktif}</p>
    <p>Non-Aktif: {total_non_aktif}</p>
  {/if}
  