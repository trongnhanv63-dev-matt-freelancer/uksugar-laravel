<x-layouts.admin>
  <x-slot:title>Dashboard</x-slot>

  <h1 class="text-2xl font-semibold text-gray-900 mb-6">Dashboard</h1>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-4 rounded-lg shadow flex justify-between items-center">
      <span class="text-sm font-medium text-gray-500">VISITS TODAY: 0</span>
      <button class="px-3 py-1 text-xs font-semibold text-white bg-black rounded hover:bg-gray-800">Reset</button>
    </div>
    <div class="bg-white p-4 rounded-lg shadow flex justify-between items-center">
      <span class="text-sm font-medium text-gray-500">VISITS THIS WEEK: 0</span>
    </div>
    <div class="bg-white p-4 rounded-lg shadow flex justify-between items-center">
      <span class="text-sm font-medium text-gray-500">ACTIVE GIRLS: 41</span>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow">
      <div class="flex justify-between items-center mb-4">
        <h2 class="font-bold text-gray-700">GIRL EDIT</h2>
        <button
          class="flex items-center px-4 py-2 text-sm font-medium text-white bg-black rounded-md hover:bg-gray-800"
        >
          Add A New Girl
          <svg
            class="w-4 h-4 ml-2"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 6v6m0 0v6m0-6h6m-6 0H6"
            ></path>
          </svg>
        </button>
      </div>
      <select class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-black">
        <option>SELECT A GIRL</option>
      </select>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
      <div class="flex justify-between items-center mb-4">
        <h2 class="font-bold text-gray-700">NOTES</h2>
        <button
          class="flex items-center px-4 py-2 text-sm font-medium text-white bg-black rounded-md hover:bg-gray-800"
        >
          Add A New Note
          <svg
            class="w-4 h-4 ml-2"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 6v6m0 0v6m0-6h6m-6 0H6"
            ></path>
          </svg>
        </button>
      </div>
    </div>
  </div>

  <div class="bg-white p-6 rounded-lg shadow">
    <h2 class="font-bold text-gray-700 mb-4">MOST POPULAR</h2>
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead>
          <tr>
            <th class="pb-2 text-sm font-medium text-gray-400">#</th>
            <th class="pb-2 text-sm font-medium text-gray-400">NAME</th>
            <th class="pb-2 text-sm font-medium text-gray-400 text-right">CLICKS</th>
          </tr>
        </thead>
        <tbody>
          @php
            $popular = [
              ['name' => 'Stacey Saran', 'clicks' => 74],
              ['name' => 'Devon', 'clicks' => 67],
              ['name' => 'Jess West', 'clicks' => 59],
              ['name' => 'Jazz', 'clicks' => 48],
              ['name' => 'Korrina', 'clicks' => 48],
              ['name' => 'Victoria Summers', 'clicks' => 45],
              ['name' => 'Yasmin', 'clicks' => 45],
              ['name' => 'Crystal', 'clicks' => 41],
              ['name' => 'Ashley', 'clicks' => 40],
              ['name' => 'Evelyn', 'clicks' => 38],
            ];
          @endphp

          @foreach ($popular as $index => $item)
            <tr class="border-t border-gray-200">
              <td class="py-3 text-gray-500">{{ $index + 1 }}.</td>
              <td class="py-3 font-medium text-gray-800">{{ $item['name'] }}</td>
              <td class="py-3 text-gray-500 text-right">{{ $item['clicks'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="flex justify-end mt-4">
      <button class="px-4 py-2 text-sm font-semibold text-white bg-black rounded hover:bg-gray-800">Reset</button>
    </div>
  </div>
</x-layouts.admin>
