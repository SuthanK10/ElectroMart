<?php include '../includes/header.php'; ?>

<!-- Hero Section -->
<section class="bg-blue-100 py-12">
  <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center px-4">
    <div class="flex-1">
      <h1 class="text-3xl font-bold mb-4">Latest Electronics at Unbeatable Prices</h1>
      <p class="text-gray-700 mb-6">Discover cutting-edge technology from smartphones to smart home devices. Quality electronics with fast shipping and excellent customer service.</p>
      <div class="space-x-3">
        <a href="products.php" class="bg-black text-white px-4 py-2 rounded">Shop Now</a>
        <a href="../pages/about.php" class="bg-gray-200 text-gray-800 px-4 py-2 rounded">About Us</a>
      </div>
      <div class="flex space-x-6 mt-6 text-sm">
        <span class="text-green-500">● Free Shipping</span>
        <span class="text-blue-500">● 2-Year Warranty</span>
        <span class="text-pink-500">● 24/7 Support</span>
      </div>
    </div>
    <div class="flex-1">
      <img src="../images/HeroLaptop.jpeg" alt="Laptop" class="rounded-lg shadow">
    </div>
  </div>
</section>

<!-- Shop by Category -->
<section class="py-12">
  <div class="max-w-7xl mx-auto px-4">
    <h2 class="text-2xl font-bold text-center mb-8">Shop by Category</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
      <div class="p-6 border rounded hover:shadow">
        <i class="fas fa-mobile-alt text-3xl mb-3 text-gray-700"></i>
        <p class="font-medium">Smartphones</p>
        <p class="text-gray-500">250+ Products</p>
      </div>
      <div class="p-6 border rounded hover:shadow">
        <i class="fas fa-laptop text-3xl mb-3 text-gray-700"></i>
        <p class="font-medium">Laptops</p>
        <p class="text-gray-500">180+ Products</p>
      </div>
      <div class="p-6 border rounded hover:shadow">
        <i class="fas fa-headphones text-3xl mb-3 text-gray-700"></i>
        <p class="font-medium">Audio</p>
        <p class="text-gray-500">320+ Products</p>
      </div>
      <div class="p-6 border rounded hover:shadow">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-700" fill="currentColor" viewBox="0 0 384 512">
        <path d="M64 48c-8.8 0-16 7.2-16 16V96H32C14.3 96 0 110.3 0 128v256c0 17.7 14.3 32 32 32H80v32c0 8.8 7.2 16 16 16H288c8.8 0 16-7.2 16-16V416h48c17.7 0 32-14.3 32-32V128c0-17.7-14.3-32-32-32H304V64c0-8.8-7.2-16-16-16H64zM80 64H288V96H80V64zM48 128H336v256H48V128zM80 416H288v32H80V416zM192 160c-44.2 0-80 35.8-80 80s35.8 80 80 80s80-35.8 80-80s-35.8-80-80-80zm0 128c-26.5 0-48-21.5-48-48s21.5-48 48-48s48 21.5 48 48s-21.5 48-48 48z"/>
        </svg>
        <p class="font-medium">Wearables</p>
        <p class="text-gray-500">150+ Products</p>
      </div>
      <div class="p-6 border rounded hover:shadow">
        <i class="fas fa-camera text-3xl mb-3 text-gray-700"></i>
        <p class="font-medium">Cameras</p>
        <p class="text-gray-500">120+ Products</p>
      </div>
      <div class="p-6 border rounded hover:shadow">
        <i class="fas fa-gamepad text-3xl mb-3 text-gray-700"></i>
        <p class="font-medium">Gaming</p>
        <p class="text-gray-500">200+ Products</p>
      </div>
      <div class="p-6 border rounded hover:shadow">
        <i class="fas fa-home text-3xl mb-3 text-gray-700"></i>
        <p class="font-medium">Smart Home</p>
        <p class="text-gray-500">300+ Products</p>
      </div>
      <div class="p-6 border rounded hover:shadow">
        <i class="fas fa-tv text-3xl mb-3 text-gray-700"></i>
        <p class="font-medium">TV & Display</p>
        <p class="text-gray-500">90+ Products</p>
      </div>
    </div>
  </div>
</section>

<!-- Featured Products -->
<section class="py-12 bg-gray-100">
  <div class="max-w-7xl mx-auto px-4">
    <h2 class="text-2xl font-bold text-center mb-8">Featured Products</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">

      <!-- iPhone -->
      <div class="bg-white p-4 rounded-lg shadow hover:shadow-xl transition-transform transform hover:-translate-y-1 hover:scale-105 duration-300 group">
        <img src="../images/iphone17pm.png" alt="iPhone 17 Pro Max" class="w-full h-48 object-contain rounded mb-4 transition-transform duration-300 group-hover:scale-110">
        <h3 class="font-semibold">iPhone 17 Pro Max</h3>
        <p class="text-black-500"><i class="fa-solid fa-star"></i> 4.8 (1234 reviews)</p>
        <p class="text-lg font-bold">$1499.99 <span class="line-through text-gray-400">$1599.99</span></p>
        <button class="mt-2 w-full bg-black text-white py-2 rounded"><a href="../pages/productsview.php?id=1">View Product</a></button>
      </div>

      <!-- MacBook Air -->
      <div class="bg-white p-4 rounded-lg shadow hover:shadow-xl transition-transform transform hover:-translate-y-1 hover:scale-105 duration-300 group">
        <img src="../images/macbookair2.jpeg" alt="MacBook Air" class="w-full h-48 object-contain rounded mb-4 transition-transform duration-300 group-hover:scale-110">
        <h3 class="font-semibold">MacBook Air</h3>
        <p class="text-black-500"><i class="fa-solid fa-star"></i> 4.8 (1234 reviews)</p>
        <p class="text-lg font-bold">$1299.99 <span class="line-through text-gray-400">$1399.99</span></p>
        <button class="mt-2 w-full bg-black text-white py-2 rounded"><a href="../pages/productsview.php?id=2">View Product</a></button>
      </div>

      <!-- Samsung Watch -->
      <div class="bg-white p-4 rounded-lg shadow hover:shadow-xl transition-transform transform hover:-translate-y-1 hover:scale-105 duration-300 group">
        <img src="../images/samsungwatch72.png" alt="Samsung Galaxy Watch 7" class="w-full h-48 object-contain rounded mb-4 transition-transform duration-300 group-hover:scale-110">
        <h3 class="font-semibold">Samsung Galaxy Watch 7</h3>
        <p class="text-black-500"><i class="fa-solid fa-star"></i> 4.8 (1234 reviews)</p>
        <p class="text-lg font-bold">$349.99 <span class="line-through text-gray-400">$449.99</span></p>
        <button class="mt-2 w-full bg-black text-white py-2 rounded"><a href="../pages/productsview.php?id=3">View Product</a></button>
      </div>

      <!-- Sony Headphones -->
      <div class="bg-white p-4 rounded-lg shadow hover:shadow-xl transition-transform transform hover:-translate-y-1 hover:scale-105 duration-300 group">
        <img src="../images/sonyhs.png" alt="Sony WH-1000XM5" class="w-full h-48 object-contain rounded mb-4 transition-transform duration-300 group-hover:scale-110">
        <h3 class="font-semibold">Sony WH-1000XM5</h3>
        <p class="text-black-500"><i class="fa-solid fa-star"></i> 4.8 (1234 reviews)</p>
        <p class="text-lg font-bold">$399.99 <span class="line-through text-gray-400">$499.99</span></p>
        <button class="mt-2 w-full bg-black text-white py-2 rounded"><a href="../pages/productsview.php?id=4">View Product</a></button>
      </div>

      <!-- iPad Pro -->
      <div class="bg-white p-4 rounded-lg shadow hover:shadow-xl transition-transform transform hover:-translate-y-1 hover:scale-105 duration-300 group">
        <img src="../images/ipadpro2.jpg" alt="iPad Pro" class="w-full h-48 object-contain rounded mb-4 transition-transform duration-300 group-hover:scale-110">
        <h3 class="font-semibold">iPad Pro</h3>
        <p class="text-black-500"><i class="fa-solid fa-star"></i> 4.8 (1234 reviews)</p>
        <p class="text-lg font-bold">$1099.99 <span class="line-through text-gray-400">$1199.99</span></p>
        <button class="mt-2 w-full bg-black text-white py-2 rounded"><a href="../pages/productsview.php?id=5">View Product</a></button>
      </div>

      <!-- Dell XPS -->
      <div class="bg-white p-4 rounded-lg shadow hover:shadow-xl transition-transform transform hover:-translate-y-1 hover:scale-105 duration-300 group">
        <img src="../images/dellxps3.png" alt="Dell XPS 13" class="w-full h-48 object-contain rounded mb-4 transition-transform duration-300 group-hover:scale-110">
        <h3 class="font-semibold">Dell XPS 13</h3>
        <p class="text-black-500"><i class="fa-solid fa-star"></i> 4.8 (1234 reviews)</p>
        <p class="text-lg font-bold">$1199.99 <span class="line-through text-gray-400">$1299.99</span></p>
        <button class="mt-2 w-full bg-black text-white py-2 rounded"><a href="../pages/productsview.php?id=6">View Product</a></button>
      </div>

      <!-- PS5 -->
      <div class="bg-white p-4 rounded-lg shadow hover:shadow-xl transition-transform transform hover:-translate-y-1 hover:scale-105 duration-300 group">
        <img src="../images/ps5.jpg" alt="PlayStation 5" class="w-full h-48 object-contain rounded mb-4 transition-transform duration-300 group-hover:scale-110">
        <h3 class="font-semibold">PlayStation 5</h3>
        <p class="text-black-500"><i class="fa-solid fa-star"></i> 4.8 (1234 reviews)</p>
        <p class="text-lg font-bold">$499.99 <span class="line-through text-gray-400">$599.99</span></p>
        <button class="mt-2 w-full bg-black text-white py-2 rounded"><a href="../pages/productsview.php?id=7">View Product</a></button>
      </div>

      <!-- Canon EOS -->
      <div class="bg-white p-4 rounded-lg shadow hover:shadow-xl transition-transform transform hover:-translate-y-1 hover:scale-105 duration-300 group">
        <img src="../images/canoneos.jpg" alt="Canon EOS R5" class="w-full h-48 object-contain rounded mb-4 transition-transform duration-300 group-hover:scale-110">
        <h3 class="font-semibold">Canon EOS R5</h3>
        <p class="text-black-500"><i class="fa-solid fa-star"></i> 4.8 (1234 reviews)</p>
        <p class="text-lg font-bold">$3899.99 <span class="line-through text-gray-400">$3999.99</span></p>
        <button class="mt-2 w-full bg-black text-white py-2 rounded"><a href="../pages/productsview.php?id=8">View Product</a></button>
      </div>
    </div>
    <div class="text-center mt-8">
      <a href="products.php" class="bg-black text-white px-6 py-2 rounded">View All Products</a>
    </div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
