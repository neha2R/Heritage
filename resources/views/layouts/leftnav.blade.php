<div class="app-sidebar sidebar-shadow">
               <div class="app-header__logo">
                  <div class="logo-src"></div>
                  <div class="header__pane ml-auto">
                     <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                        <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                        </span>
                        </button>
                     </div>
                  </div>
               </div>
               <div class="app-header__mobile-menu">
                  <div>
                     <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                     <span class="hamburger-box">
                     <span class="hamburger-inner"></span>
                     </span>
                     </button>
                  </div>
               </div>
               <div class="app-header__menu">
                  <span>
                  <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                  <span class="btn-icon-wrapper">
                  <i class="fa fa-ellipsis-v fa-w-6"></i>
                  </span>
                  </button>
                  </span>
               </div>
               <div class="scrollbar-sidebar">
                  <div class="app-sidebar__inner">
                     <ul class="vertical-nav-menu">
                     <li class="app-sidebar__heading">Quiz Speed</li>
                        <li>
                           <a href="{{ route('quiztype.index') }}" class="{{ request()->is('admin/quiztype*') ? 'mm-active' : '' }}">
                           <i class="metismenu-icon pe-7s-rocket"></i>
                          Quiz Type
                           </a>
                        </li>

                        <li class="app-sidebar__heading">Domain</li>
                        <li>
                           <a href="{{ route('domain.index') }}" class="{{ request()->is('admin/domain*') ? 'mm-active' : '' }}">
                           <i class="metismenu-icon pe-7s-rocket"></i>
                           Add Domain
                           </a>
                        </li>
                        <li class="app-sidebar__heading">Age Group</li>
                        <li>
                           <a href="{{ route('agegroup.index') }}" class="{{ request()->is('admin/agegroup*') ? 'mm-active' : '' }}">
                           <i class="metismenu-icon pe-7s-rocket"></i>
                           Age Group
                           </a>
                        </li>
                        <li
                           >
                           <!-- <a href="#">
                           <i class="metismenu-icon pe-7s-diamond"></i>
                           Elements
                           <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                           </a> -->
                           <ul
                              >
                              <li>
                                 <a href="elements-buttons-standard.html">
                                 <i class="metismenu-icon"></i>
                                 Buttons
                                 </a>
                              </li>
                              <li>
                                 <a href="elements-dropdowns.html">
                                 <i class="metismenu-icon">
                                 </i>Dropdowns
                                 </a>
                              </li>
                           </ul>
                        </li>
                        <li class="app-sidebar__heading">Difficulty Level</li>
                        <li>
                           <a href="{{ route('difflevel.index') }}" class="{{ request()->is('admin/difflevel*') ? 'mm-active' : '' }}">
                           <i class="metismenu-icon pe-7s-rocket"></i>
                          Difficulty Level
                           </a>
                        </li>
                        <li class="app-sidebar__heading">Quiz Speed</li>
                        <li>
                           <a href="{{ route('quizspeed.index') }}" class="{{ request()->is('admin/quizspeed*') ? 'mm-active' : '' }}">
                           <i class="metismenu-icon pe-7s-rocket"></i>
                          Quiz Speed
                           </a>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
