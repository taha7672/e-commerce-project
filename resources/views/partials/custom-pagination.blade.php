@if ($paginator->hasPages())
    <ul class="flex items-center justify-center gap-2">
         {{-- Previous Page Link --}}
         @if ($paginator->onFirstPage())
            <li class="group disabled">
                <span
                class="flex w-10 h-10 items-center justify-center border border-borderColour dark:border-borderColour-dark rounded-full hover:bg-primary duration-300  text-sm font-medium group">
                    <i class="fa-solid fa-arrow-left dark:group-hover:text-paragraph duration-300"></i>
                </span>
            </li>
        @else
            <li class="group">
                <a
                href="{{ $paginator->previousPageUrl() }}"
                class="flex w-10 h-10 items-center justify-center border border-borderColour dark:border-borderColour-dark rounded-full hover:bg-primary duration-300  text-sm font-medium group"
                >
                <i class="fa-solid fa-arrow-left dark:group-hover:text-paragraph duration-300"></i>
                </a>
            </li>
        @endif


       {{-- Pagination Links --}}
       @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <li class="group {{ ($paginator->currentPage() == $i) ? 'page-active' : '' }}">
                <a
                  href="{{ $paginator->url($i) }}"
                  class="flex w-10 h-10 items-center text-sm font-medium justify-center rounded-full hover:bg-primary duration-300  hover:text-paragraph group-[.page-active]:bg-primary dark:group-[.page-active]:text-paragraph"
                >
                  {{$i}}
                </a>
              </li>
        @endfor

          {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
          <li class="group">
            <a
              href="{{ $paginator->nextPageUrl() }}"
              class="flex w-10 h-10 items-center justify-center border border-borderColour dark:border-borderColour-dark rounded-full hover:bg-primary duration-300  text-sm font-medium group"
            >
              <i class="fa-solid fa-arrow-right dark:group-hover:text-paragraph duration-300"></i>
            </a>
          </li>
      @else
          <li class="group disabled">
            <span
              class="flex w-10 h-10 items-center justify-center border border-borderColour dark:border-borderColour-dark rounded-full hover:bg-primary duration-300  text-sm font-medium group">
              <i class="fa-solid fa-arrow-right dark:group-hover:text-paragraph duration-300"></i>
          </span>
          </li>
      @endif

    </ul>
@endif
