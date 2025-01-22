<script lang="ts">
	import '../app.css';
	import { onMount } from "svelte";
	import { goto } from '$app/navigation';
	import axios from "axios";
  
	let { children } = $props();
	let isAuthenticated = false;
  
	const checkAuth = async () => {
	  try {
		// Ambil token dari localStorage
		const token = localStorage.getItem("auth_token");
  
		if (!token) {
		  throw new Error("Unauthenticated");
		}
  
		// Periksa token dengan request API yang memerlukan autentikasi
		await axios.get("http://localhost:8000/api/karyawan", {
		  headers: {
			Authorization: `Bearer ${token}`,
		  },
		});
  
		isAuthenticated = true;
	  } catch (error) {
		isAuthenticated = false;
  
		// Redirect ke halaman login jika token tidak valid
		goto("/");
	  }
	};
  
	const logout = () => {
		localStorage.removeItem('auth_token');
		isAuthenticated = false;
		goto('/');
	};

	onMount(() => {
		const token = localStorage.getItem('auth_token');
		isAuthenticated = !!token; // Set true jika token ada
		if(!isAuthenticated){

		}
		console.log(isAuthenticated);
	});
</script>

<nav>
  <a href="/" style="display: block;">Home</a>
  <a href="/dashboard">Dashboard</a>
  <a href="/karyawan">Karyawan</a>
  <a href="/pekerjaan">Pekerjan</a>
  <a href="/divisi">Divisi</a>
  <button on:click={logout}>Logout</button>
</nav>

{@render children()}
  