<script>
    import { onMount } from 'svelte';
    import axios from 'axios';
  
    let divisi = [];
    let formData = { id: null, nama_divisi: '' };
    let loading = true;
    let error = null;
    let showForm = false;
    let isEditing = false;
  
    const token = localStorage.getItem('auth_token');
  
    // Fetch divisi
    const fetchDivisi = async () => {
      loading = true;
      error = null;
  
      try {
        const response = await axios.get('http://localhost:8000/api/divisi', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        divisi = response.data;
      } catch (err) {
        error = err.response?.data?.message || err.message;
      } finally {
        loading = false;
      }
    };
  
    // Tambah/Edit divisi
    const saveDivisi = async () => {
      try {
        if (isEditing) {
          await axios.put(`http://localhost:8000/api/divisi/${formData.id}`, formData, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
        } else {
          await axios.post('http://localhost:8000/api/divisi', formData, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
        }
  
        await fetchDivisi();
        resetForm();
      } catch (err) {
        error = err.response?.data?.message || err.message;
      }
    };
  
    // Hapus divisi
    const deleteDivisi = async (id) => {
      if (confirm('Apakah Anda yakin ingin menghapus divisi ini?')) {
        try {
          await axios.delete(`http://localhost:8000/api/divisi/${id}`, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          await fetchDivisi();
        } catch (err) {
          error = err.response?.data?.message || err.message;
        }
      }
    };
  
    // Reset form
    const resetForm = () => {
      formData = { id: null, nama_divisi: '' };
      showForm = false;
      isEditing = false;
    };
  
    // Edit divisi
    const editDivisi = (data) => {
      formData = { ...data };
      isEditing = true;
      showForm = true;
    };
  
    // Initial fetch
    onMount(() => {
      fetchDivisi();
    });
  </script>
  
  {#if loading}
    <p>Loading...</p>
  {:else if error}
    <p>Error: {error}</p>
  {:else}
    <div>
      <h1>Daftar Divisi</h1>
  
      <button on:click={() => (showForm = true)}>Tambah Divisi</button>
  
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama Divisi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          {#each divisi as p}
            <tr>
              <td>{p.id}</td>
              <td>{p.nama_divisi}</td>
              <td>
                <button on:click={() => editDivisi(p)}>Edit</button>
                <button on:click={() => deleteDivisi(p.id)}>Hapus</button>
              </td>
            </tr>
          {/each}
        </tbody>
      </table>
  
      {#if showForm}
        <div>
          <h2>{isEditing ? 'Edit Divisi' : 'Tambah Divisi'}</h2>
          <form on:submit|preventDefault={saveDivisi}>
            <div>
              <label for="nama_divisi">Nama Divisi</label>
              <input
                type="text"
                id="nama_divisi"
                bind:value={formData.nama_divisi}
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
  