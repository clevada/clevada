<li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownLangs" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-globe"></i> <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownLangs">
                            @foreach (languages() as $nav_lang)
                                <li><a class="dropdown-item" @if ($nav_lang->is_default == 1) href="{{ config('app.url') }}" @else href="{{ config('app.url') }}/{{ $nav_lang->code }}" @endif>{{ $nav_lang->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>