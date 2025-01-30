<script>
  import { onMount } from 'svelte';

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
          const response = await fetch('http://localhost:8000/api/divisi', {
              headers: {
                  Authorization: `Bearer ${token}`,
              },
          });

          if (!response.ok) {
              throw new Error('Gagal memuat data');
          }

          divisi = await response.json();
      } catch (err) {
          error = err.message;
          window.location.href = '/';
      } finally {
          loading = false;
      }
  };

  // Tambah/Edit divisi
  const saveDivisi = async () => {
      try {
          const url = isEditing
              ? `http://localhost:8000/api/divisi/${formData.id}`
              : 'http://localhost:8000/api/divisi';

          const method = isEditing ? 'PUT' : 'POST';

          const response = await fetch(url, {
              method,
              headers: {
                  'Content-Type': 'application/json',
                  Authorization: `Bearer ${token}`,
              },
              body: JSON.stringify(formData),
          });

          if (!response.ok) {
              throw new Error('Gagal menyimpan data');
          }

          await fetchDivisi();
          resetForm();
      } catch (err) {
          error = err.message;
      }
  };

  // Hapus divisi
  const deleteDivisi = async (id) => {
      if (confirm('Apakah Anda yakin ingin menghapus divisi ini?')) {
          try {
              const response = await fetch(`http://localhost:8000/api/divisi/${id}`, {
                  method: 'DELETE',
                  headers: {
                      Authorization: `Bearer ${token}`,
                  },
              });

              if (!response.ok) {
                  throw new Error('Gagal menghapus data');
              }

              await fetchDivisi();
          } catch (err) {
              error = err.message;
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
