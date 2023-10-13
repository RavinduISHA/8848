<nav class="bg-gradient-to-b from-[#166EB6] to-[#284297]">
  <div class="px-2 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="relative flex items-center justify-between h-16">
      <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
        <!-- Mobile menu button-->
        <button type="button" class="relative inline-flex items-center justify-center p-2 text-gray-400 rounded-md hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
          <span class="absolute -inset-0.5"></span>
          <span class="sr-only">Open main menu</span>
          <!--
              Icon when menu is closed.
  
              Menu open: "hidden", Menu closed: "block"
            -->
          <svg class="block w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
          <!--
              Icon when menu is open.
  
              Menu open: "block", Menu closed: "hidden"
            -->
          <svg class="hidden w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      {{-- logo --}}
      <div class="items-center justify-center flex-1 md:ml-0 ml-[80px] sm:items-stretch sm:justify-start">
        <img class="w-auto h-10" src="{{ URL('images/Logo-white.png')}}" alt="Company">
      </div>

      {{-- search bar --}}
      <x-serach />

      {{-- <div class="flex items-center justify-center flex-1 sm:items-stretch sm:justify-end"></div> --}}

      <div class="flex">
        <button id="user-icon-button" type="button" class="relative p-1 mr-4 text-gray-400 rounded-full hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
          <span class="absolute -inset-1.5"></span>
          <span class="sr-only">Open user menu</span>
          <img class="w-10 h-10 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
        </button>
        <div class="flex flex-col ml-4 text-sm text-white">
          <span class="">User Doe</span>
          <span class="text-[12px]">hasindu@example.com</span>
        </div>
      </div>
    </div>

    {{--user icon details --}}
    <div class="absolute right-[250px] z-10 hidden w-48 py-1 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" id="user-icon-details">
      <!-- Active: "bg-gray-100", Not Active: "" -->
      <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:text-white hover:bg-blue-600" role="menuitem" tabindex="-1" id="user-menu-item-0">Edit Profile</a>
      <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:text-white hover:bg-blue-600" role="menuitem" tabindex="-1" id="user-menu-item-1">Settings</a>
      <form action="/logout" method="post">
        @csrf
        <button class="block px-4 py-2 text-sm text-gray-700 hover:text-blue-500" role="menuitem" tabindex="-1" id="user-menu-item-2">Sign out</button>
      </form>
    </div>
  </div>

</nav>

<script>
  // Get references to the button and the user icon details section
  const userIconButton = document.getElementById("user-icon-button");
  const userIconDetails = document.getElementById("user-icon-details");

  // Add a click event listener to the button
  userIconButton.addEventListener("click", () => {
    // Toggle the visibility of the user icon details section
    if (userIconDetails.style.display === "none" || userIconDetails.style.display === "") {
      userIconDetails.style.display = "block";
    } else {
      userIconDetails.style.display = "none";
    }
  });

  const dropdownButton = document.getElementById("dropdown-button");
  const dropdownDetails = document.getElementById("dropdown-details");

  // Add a click event listener to the button
  dropdownButton.addEventListener("click", () => {
    // Toggle the visibility of the user icon details section
    if (dropdownDetails.style.display === "none" || dropdownDetails.style.display === "") {
      dropdownDetails.style.display = "block";
    } else {
      dropdownDetails.style.display = "none";
    }
  });
</script>