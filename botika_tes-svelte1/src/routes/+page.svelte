<script>
    import { onMount } from "svelte";
    import { goto } from "$app/navigation";
    import axios from "axios";
  
    let email = "";
    let password = "";
    let errorMessage = "";
  
    const login = async () => {
      try {
        const response = await axios.post("http://localhost:8000/api/login", {
          email,
          password,
        });
  
        // Simpan token ke localStorage
        localStorage.setItem("auth_token", response.data.token);
  
        // Redirect ke halaman utama
        goto("/dashboard");
      } catch (error) {
        errorMessage = error.response?.data?.message || "Login failed";
      }
    };
  </script>
  
  <div>
    <h1>Login</h1>
    <form on:submit|preventDefault={login}>
      <div>
        <label for="email">Email:</label>
        <input type="email" id="email" bind:value={email} required />
      </div>
      <div>
        <label for="password">Password:</label>
        <input type="password" id="password" bind:value={password} required />
      </div>
      {#if errorMessage}
        <p style="color: red;">{errorMessage}</p>
      {/if}
      <button type="submit">Login</button>
    </form>
  </div>
  