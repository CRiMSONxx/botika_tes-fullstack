<script>
    import { onMount } from 'svelte';
    let karyawans = [];
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
    <input type="number" placeholder="Divisi ID" bind:value={newKaryawan.divisi_id} required />
    <input type="number" placeholder="Pekerjaan ID" bind:value={newKaryawan.pekerjaan_id} required />
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
  