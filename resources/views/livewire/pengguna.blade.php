<div>
    <!-- CONTENT -->
    <div class="ms-10 mt-10 me-10 flex flex-col flex-grow">
        <h1>On Progress!!</h1>
        <span class="flex items-center p-2 text-gray-900 rounded-lg group">
            <img src="/img/icons/pengguna.png" class="flex-shrink-0 w-8 h-8 text-gray-500" aria-hidden="true" fill="currentColor">
            <span class="flex-1 ms-4 whitespace-nowrap text-3xl font-bold">Data Pengguna</span>
        </span>

        <div class="container font-inter">
            <div class="flex flex-wrap">

                <!-- Tabel dari database nanti nya -->

                <div class="mt-7">
                    <a href="" class="flex items-center p-2 text-gray-900 border-2 rounded-lg bg-slate-100 hover:bg-sky-500 hover:text-white transition duration-700 group">
                        <img src="/img/icons/tambahdata.png" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" aria-hidden="true" fill="currentColor">
                        <span class="flex-1 ms-3 whitespace-nowrap">Tambah Data</span>
                    </a>
                </div>
            </div>

            <div class="flex flex-col mt-5">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <table class="min-w-full text-center text-sm text-surface">
                                <thead class="border-b border-neutral-200 font-medium">
                                    <tr>
                                        <th scope="col" class="px-6 py-4">No</th>
                                        <th scope="col" class="px-6 py-4">ID Pengguna</th>
                                        <th scope="col" class="px-6 py-4">Nama</th>
                                        <th scope="col" class="px-6 py-4">Email</th>
                                        <th scope="col" class="px-6 py-4">Role</th>
                                        <th scope="col" class="px-6 py-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-secondary-200 bg-secondary-100 text-neutral-800">
                                        <td class="whitespace-nowrap px-6 py-4 font-medium">1</td>
                                        <td class="whitespace-nowrap px-6 py-4">0001</td>
                                        <td class="whitespace-nowrap px-6 py-4">Muhammad Dafa Putra</td>
                                        <td class="whitespace-nowrap px-6 py-4">muhammaddafaputraoffcl@gmail.com</td>
                                        <td class="whitespace-nowrap px-6 py-4">Super Admin</td>
                                        <td class="whitespace-nowrap px-6 py-4">

                                            <!-- Edit -->
                                            <button data-modal-target="large-modal" data-modal-toggle="large-modal" class="flex-none p-2.5 bg-sky-500 rounded-xl hover:rounded-3xl hover:bg-sky-600 transition-all duration-300 text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>



                                            <!-- Delete -->
                                            <button data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="flex-none p-2.5 bg-red-500 rounded-xl hover:rounded-3xl hover:bg-red-600 transition-all duration-300 text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>