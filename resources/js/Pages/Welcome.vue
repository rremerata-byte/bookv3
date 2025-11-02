<template>
    <header>
      <nav class="navbar">
        <a class="logo" href="#">
          <img src="/img/loho.png" alt="Logo" class="logo-img">
        </a>
        
      </nav>
    </header>
  
    <section class="hero-section">
      <div class="content boxed-content">
        <div style="text-align: center;">
          <h1>BOOK CLOUD</h1>
          <br>
          <h4>ENCHANCING LIBRARY ACCESS AND MANAGEMENT EFFICIENCY</h4>
        </div>
        <br>
      </div>
      <Link href="/login" class="get-started-btn">GET STARTED</Link>
    </section>
  </template>
  
  <script>
  import { Link } from '@inertiajs/vue3'

  export default {
    name: "Home",
    components: {
      Link
    }
  };
  </script>
  
  <style scoped>
  @import "/public/css/home.css";  /* Use @ to refer to the resources directory */
  </style>
  