<script>
    import { onMount } from 'svelte';
    let karyawans = [];
    let divisiList = [];
    let pekerjaanList = [];
    let newKaryawan = {
      nama: '',
      email: '',
      no_telepon: '',
      tanggal_lahir: '',
      divisi_id: '',
      pekerjaan_id: ''
    };
    let editingKaryawan = null;
    let errorMessage = '';
  
    // Retrieve Bearer token from localStorage
    const getAuthToken = () => localStorage.getItem('auth_token');
  
    // Fetch all karyawans on mount
    onMount(async () => {
      try {
        const response = await fetch('/api/karyawan', {
          headers: {
            'Authorization': `Bearer ${getAuthToken()}`,
          }
        });
        const data = await response.json();
        karyawans = data;
      } catch (error) {
        errorMessage = 'Failed to load karyawan data';
      }
    });
  
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
  
    // Create or Update karyawan
    const saveKaryawan = async () => {
      try {
        let response;
        const headers = {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${getAuthToken()}`,
        };
  
        if (editingKaryawan) {
          response = await fetch(`/api/karyawan/${editingKaryawan.id}`, {
            method: 'PUT',
            headers,
            body: JSON.stringify(newKaryawan)
          });
        } else {
          response = await fetch('/api/karyawan', {
            method: 'POST',
            headers,
            body: JSON.stringify(newKaryawan)
          });
        }
  
        if (!response.ok) throw new Error('Failed to save karyawan');
  
        // Reset the form and fetch updated data
        newKaryawan = { nama: '', email: '', no_telepon: '', tanggal_lahir: '', divisi_id: '', pekerjaan_id: '' };
        editingKaryawan = null;
        const updatedData = await response.json();
        karyawans = updatedData;
      } catch (error) {
        errorMessage = error.message;
      }
    };
  
    // Edit karyawan
    const editKaryawan = (karyawan) => {
      editingKaryawan = karyawan;
      newKaryawan = { ...karyawan };
    };
  
    // Delete karyawan
    const deleteKaryawan = async (id) => {
      try {
        const response = await fetch(`/api/karyawan/${id}`, {
          method: 'DELETE',
          headers: {
            'Authorization': `Bearer ${getAuthToken()}`,
          }
        });
        if (!response.ok) throw new Error('Failed to delete karyawan');
        karyawans = karyawans.filter(karyawan => karyawan.id !== id);
      } catch (error) {
        errorMessage = error.message;
      }
    };
  </script>
  
  <h1>Karyawan Management</h1>
  
  {#if errorMessage}
    <p class="error">{errorMessage}</p>
  {/if}
  
  <!-- Form for adding/updating karyawan -->
  <form on:submit|preventDefault={saveKaryawan}>
    <input type="text" placeholder="Nama" bind:value={newKaryawan.nama} required />
    <input type="email" placeholder="Email" bind:value={newKaryawan.email} required />
    <input type="text" placeholder="No Telepon" bind:value={newKaryawan.no_telepon} required />
    <input type="date" placeholder="Tanggal Lahir" bind:value={newKaryawan.tanggal_lahir} required />
    <!-- <input type="number" placeholder="Divisi ID" bind:value={newKaryawan.divisi_id} required /> -->
    <input type="number" placeholder="Pekerjaan ID" bind:value={newKaryawan.pekerjaan_id} required />
    
    <div>
      <label for="divisi">Filter berdasarkan divisi:</label>
      <select id="divisi" bind:value={newKaryawan.divisi_id} required>
        {#each divisiList as divisi}
          <option value={divisi.id}>{divisi.nama_divisi}</option>
        {/each}
      </select>
    </div>
    <button type="submit">{editingKaryawan ? 'Update' : 'Create'} Karyawan</button>
  </form>
  
  <!-- Display karyawan list -->
  <h2>List of Karyawan</h2>
  <ul>
    {#each karyawans as karyawan}
      <li>
        {karyawan.nama} - {karyawan.email}
        <button on:click={() => editKaryawan(karyawan)}>Edit</button>
        <button on:click={() => deleteKaryawan(karyawan.id)}>Delete</button>
      </li>
    {/each}
  </ul>
  