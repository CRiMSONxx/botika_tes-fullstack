<script>
    import { onMount } from 'svelte';
    import axios from 'axios';
  
    let karyawan = [];
    let divisiList = [];
    let pekerjaanList = [];
    let formData = { id: null, nama: '', email: '', no_telepon: '', tanggal_lahir: '', divisi_id: '', pekerjaan_id: '' };
    let loading = true;
    let error = null;
    let showForm = false;
    let isEditing = false;
  
    const token = localStorage.getItem('auth_token');
  
    // Fetch karyawan
    const fetchKaryawan = async () => {
      loading = true;
      error = null;
  
      try {
        const response = await axios.get('http://localhost:8000/api/karyawans', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        karyawan = response.data;
      } catch (err) {
        error = err.response?.data?.message || err.message;
      } finally {
        loading = false;
      }
    };
    
  // Fetch divisi
  const fetchDivisi = async () => {
      loading = true;
      error = null;

      try {
          const response = await fetch('http://localhost:8000/api/divisi', {
              headers: {
                  Authorization: `Bearer ${token}`,
              },
          });

          if (!response.ok) {
              throw new Error('Gagal memuat data');
          }

          divisiList = await response.json();
          console.log(divisiList);
      } catch (err) {
          error = err.message;
          window.location.href = '/';
      } finally {
          loading = false;
      }
  };
    const fetchPekerjaan = async () => {
      loading = true;
      error = null;
  
      try {
        const response = await axios.get('http://localhost:8000/api/pekerjaan', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        pekerjaanList = response.data;
        console.log(pekerjaanList);
      } catch (err) {
        error = err.response?.data?.message || err.message;
      } finally {
        loading = false;
      }
    };
  
    // Tambah/Edit karyawan
    const saveKaryawan = async () => {
      try {
        if (isEditing) {
          await axios.put(`http://localhost:8000/api/karyawan/${formData.id}`, formData, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
        } else {
          await axios.post('http://localhost:8000/api/karyawan', formData, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
        }
  
        await fetchKaryawan();
        resetForm();
      } catch (err) {
        error = err.response?.data?.message || err.message;
      }
    };
  
    // Hapus karyawan
    const deleteKaryawan = async (id) => {
      if (confirm('Apakah Anda yakin ingin menghapus karyawan ini?')) {
        try {
          await axios.delete(`http://localhost:8000/api/karyawan/${id}`, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          await fetchKaryawan();
        } catch (err) {
          error = err.response?.data?.message || err.message;
        }
      }
    };
  
    // Reset form
    const resetForm = () => {
      formData = { id: null, nama_karyawan: '' };
      showForm = false;
      isEditing = false;
    };
  
    // Edit karyawan
    const editKaryawan = (data) => {
      formData = { ...data };
      isEditing = true;
      showForm = true;
    };
  
    // Initial fetch
    onMount(() => {
      fetchKaryawan();
      fetchDivisi();
      fetchPekerjaan();
      console.log(karyawan);
    });
  </script>
  
  {#if loading}
    <p>Loading...</p>
  {:else if error}
    <p>Error: {error}</p>
  {:else}
    <div>
      <h1>Daftar Karyawan</h1>
  
      <button on:click={() => (showForm = true)}>Tambah Karyawan</button>
  
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>No Telepon</th>
            <th>Tanggal Lahir</th>
            <th>Divisi</th>
            <th>Pekerjaan</th>
          </tr>
        </thead>
        <tbody>
          {#each karyawan as p}
            <tr>
              <td>{p.id}</td>
              <td>{p.nama}</td>
              <td>{p.email}</td>
              <td>{p.no_telepon}</td>
              <td>{new Date(p.tanggal_lahir).toLocaleDateString()}</td>
              <td>{p.divisi.nama_divisi}</td>
              <td>{p.pekerjaan.nama_pekerjaan}</td>
              <td>
                <button on:click={() => editKaryawan(p)}>Edit</button>
                <button on:click={() => deleteKaryawan(p.id)}>Hapus</button>
              </td>
            </tr>
          {/each}
        </tbody>
      </table>
  
      {#if showForm}
        <div>
          <h2>{isEditing ? 'Edit Karyawan' : 'Tambah Karyawan'}</h2>
          <form on:submit|preventDefault={saveKaryawan}>
            <div>
              <label for="nama">Nama Karyawan</label>
              <input
                type="text"
                id="nama"
                bind:value={formData.nama}
                required
              />
            </div>
                <input type="email" placeholder="Email" bind:value={formData.email} required />
                <input type="text" placeholder="No Telepon" bind:value={formData.no_telepon} required />
                <input type="date" placeholder="Tanggal Lahir" bind:value={formData.tanggal_lahir} required />
                <!-- <input type="number" placeholder="Divisi ID" bind:value={formData.divisi_id} required />
                <input type="number" placeholder="Pekerjaan ID" bind:value={formData.pekerjaan_id} required /> -->
              <div>
                <label for="divisi">Filter berdasarkan divisi:</label>
                <select id="divisi" bind:value={formData.divisi_id} required>
                  {#each divisiList as divisi}
                    <option value={divisi.id}>{divisi.nama_divisi}</option>
                  {/each}
                </select>
              </div>
              <div>
                <label for="pekerjaan">Filter berdasarkan pekerjaan:</label>
                <select id="pekerjaan" bind:value={formData.pekerjaan_id} required>
                  {#each pekerjaanList as pekerjaan}
                    <option value={pekerjaan.id}>{pekerjaan.nama_pekerjaan}</option>
                  {/each}
                </select>
              </div>
            <div>
              <button type="submit">{isEditing ? 'Simpan Perubahan' : 'Tambah'}</button>
              <button type="button" on:click={resetForm}>Batal</button>
            </div>
          </form>
        </div>
      {/if}
    </div>
  {/if}
  