<div>
    <!-- dropdown -->
    <div class="relative" data-twe-dropdown-ref>
        <a class="flex items-center rounded bg-[#ED9455] px-6 pb-2 pt-2.5 text-xs font-medium leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-[#F97300] hover:shadow-primary-2 focus:bg-[#ED9455]-accent-300 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-[#ED9455] active:shadow-primary-2 motion-reduce:transition-none" href="#" type="button" id="dropdownMenuButton2" data-twe-dropdown-toggle-ref aria-expanded="false" data-twe-ripple-color="light">

            <span class=" w-3 mr-3">
                <img src="/img/user.png" alt="user">
            </span>
            <span class="font-inter text-sm">Administrator</span>
        </a>
        <ul class="absolute z-[1000] float-left m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-base shadow-lg data-[twe-dropdown-show]:block" aria-labelledby="dropdownMenuButton2" data-twe-dropdown-menu-ref>
            <li>
                <button class="block w-full whitespace-nowrap bg-white px-4 py-2 text-sm font-normal text-neutral-700 hover:bg-zinc-200/60 focus:bg-zinc-200/60 focus:outline-none active:bg-zinc-200/60 active:no-underline" data-twe-dropdown-item-ref>Pengaturan</a>
            </li>
            <li>
                <button wire:click="logout" class="block w-full whitespace-nowrap bg-white px-4 py-2 text-sm font-normal text-neutral-700 hover:bg-zinc-200/60 focus:bg-zinc-200/60 focus:outline-none active:bg-zinc-200/60 active:no-underline" data-twe-dropdown-item-ref>Logout</a>
            </li>
        </ul>
    </div>
</div>
</nav>
</div>