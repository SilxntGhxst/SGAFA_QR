@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="flex flex-1 min-h-screen font-['Inter']">

   <div class="hidden lg:flex lg:w-[55%] bg-white flex-col p-16 justify-between">
      <div class="flex items-center gap-3">
         <div class="w-10 h-10 bg-sgafa-primary rounded-lg flex items-center justify-center shadow-lg">
            <img src="{{ asset('img/logo.png') }}" alt="QR" class="w-7 h-7 object-contain brightness-0 invert">
         </div>
         <span class="text-3xl font-bold text-sgafa-primary tracking-tight">S.G.A.F.A QR</span>
      </div>

      <div class="flex justify-center my-8">
         <div class="relative w-full max-w-lg">
            <img src="{{ asset('img/login_illustration.png') }}" alt="Ilustración Gestión" class="w-full h-auto drop-shadow-2xl">
         </div>
      </div>

      <div class="max-w-xl">
         <h1 class="text-5xl font-extrabold text-sgafa-primary leading-[1.1] mb-4">
            Sistema de Gestión de <span class="text-[#E5B181]">Activos</span> <span class="text-sgafa-accent">Fijos</span> y Auditoría con QR
         </h1>
         <p class="text-sgafa-text-muted text-lg font-medium leading-relaxed mb-8">
            Optimiza el control y seguimiento de activos de manera fácil y rápida escaneando códigos QR.
         </p>

         <div class="flex gap-4 mb-8">
            <span class="px-5 py-2 rounded-full border border-slate-200 bg-white shadow-sm flex items-center gap-2 text-sm font-bold text-sgafa-primary">
               <span class="text-[#E5B181]">📦</span> Inventario Real
            </span>
            <span class="px-5 py-2 rounded-full border border-slate-200 bg-white shadow-sm flex items-center gap-2 text-sm font-bold text-sgafa-primary">
               <span class="text-sgafa-accent">||||</span> Escaneo Rápido
            </span>
         </div>
      </div>

      <p class="text-xs font-bold text-slate-400">© 2026 S.G.A.F.A QR System. All rights reserved.</p>
   </div>

   <div class="w-full lg:w-[45%] bg-[#E5E7EB] flex items-center justify-center p-6 sm:p-12">
      <div class="w-full max-w-md bg-white rounded-[2rem] shadow-2xl p-10 sm:p-14 border border-white/50">

         <div class="mb-12">
            <h2 class="text-4xl font-extrabold text-sgafa-primary mb-3 tracking-tight text-center lg:text-left">Iniciar sesión</h2>
            <p class="text-sgafa-text-muted font-semibold text-lg text-center lg:text-left">Bienvenido de nuevo al panel de control</p>
         </div>

         <form action="#" method="POST" class="space-y-5">
            <div class="relative group">
               <input type="email" id="email" name="email" placeholder="Correo electrónico"
                  class="w-full px-6 py-4 bg-[#F3F4F6] border border-slate-300 rounded-xl text-sgafa-text-main placeholder:text-slate-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sgafa-accent/50 focus:border-sgafa-accent transition-all font-medium">
            </div>

            <div class="relative group">
               <input type="password" id="password" name="password" placeholder="Contraseña"
                  class="w-full px-6 py-4 bg-[#F3F4F6] border border-slate-300 rounded-xl text-sgafa-text-main placeholder:text-slate-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sgafa-accent/50 focus:border-sgafa-accent transition-all font-medium">
            </div>

            <button type="button"
               class="w-full bg-[#4A86B4] text-white font-bold text-xl py-4 rounded-xl hover:bg-[#3b6d94] hover:shadow-lg transition-all duration-300 mt-4 active:scale-[0.98]">
               Iniciar Sesión
            </button>

            <div class="text-center mt-6">
               <a href="#" class="text-sm font-bold text-slate-500 hover:text-sgafa-accent underline decoration-slate-300 underline-offset-4 transition-colors">
                  ¿Olvidaste tu contraseña?
               </a>
            </div>
         </form>

      </div>
   </div>

</div>
@endsection