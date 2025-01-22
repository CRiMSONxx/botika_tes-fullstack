<script>
    import { onMount } from 'svelte';
    import axios from 'axios';
  
    let pekerjaan = [];
    let formData = { id: null, nama_pekerjaan: '' };
    let loading = true;
    let error = null;
    let showForm = false;
    let isEditing = false;
  
    const token = localStorage.getItem('auth_token');
  
    // Fetch pekerjaan
    const fetchPekerjaan = async () => {
      loading = true;
      error = null;
  
      try {
        const response = await axios.get('http://localhost:8000/api/pekerjaan', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        pekerjaan = response.data;
      } catch (err) {
        error = err.response?.data?.message || err.message;
      } finally {
        loading = false;
      }
    };
  
    // Tambah/Edit pekerjaan
    const savePekerjaan = async () => {
      try {
        if (isEditing) {
          await axios.put(`http://localhost:8000/api/pekerjaan/${formData.id}`, formData, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
        } else {
          await axios.post('http://localhost:8000/api/pekerjaan', formData, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
        }
  
        await fetchPekerjaan();
        resetForm();
      } catch (err) {
        error = err.response?.data?.message || err.message;
      }
    };
  
    // Hapus pekerjaan
    const deletePekerjaan = async (id) => {
      if (confirm('Apakah Anda yakin ingin menghapus pekerjaan ini?')) {
        try {
          await axios.delete(`http://localhost:8000/api/pekerjaan/${id}`, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          await fetchPekerjaan();
        } catch (err) {
          error = err.response?.data?.message || err.message;
        }
      }
    };
  
    // Reset form
    const resetForm = () => {
      formData = { id: null, nama_pekerjaan: '' };
      showForm = false;
      isEditing = false;
    };
  
    // Edit pekerjaan
    const editPekerjaan = (data) => {
      formData = { ...data };
      isEditing = true;
      showForm = true;
    };
  
    // Initial fetch
    onMount(() => {
      fetchPekerjaan();
    });
  </script>
  
  {#if loading}
    <p>Loading...</p>
  {:else if error}
    <p>Error: {error}</p>
  {:else}
    <div>
      <h1>Daftar Pekerjaan</h1>
  
      <button on:click={() => (showForm = true)}>Tambah Pekerjaan</button>
  
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama Pekerjaan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          {#each pekerjaan as p}
            <tr>
              <td>{p.id}</td>
              <td>{p.nama_pekerjaan}</td>
              <td>
                <button on:click={() => editPekerjaan(p)}>Edit</button>
                <button on:click={() => deletePekerjaan(p.id)}>Hapus</button>
              </td>
            </tr>
          {/each}
        </tbody>
      </table>
  
      {#if showForm}
        <div>
          <h2>{isEditing ? 'Edit Pekerjaan' : 'Tambah Pekerjaan'}</h2>
          <form on:submit|preventDefault={savePekerjaan}>
            <div>
              <label for="nama_pekerjaan">Nama Pekerjaan</label>
              <input
                type="text"
                id="nama_pekerjaan"
                bind:value={formData.nama_pekerjaan}
                required
              />
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
  