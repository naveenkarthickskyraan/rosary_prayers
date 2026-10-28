@php
  $filename =  request()->segments();
  $file= request()->segment(count(request()->segments()));
@endphp

<style>
  .alert{
    border: 0;
    position: relative;
    padding: 0.95rem 1.25rem;
    border-radius: 1px;
    color: inherit;
    background-color: #fff;
    border-left: 5px solid red !important;
    -webkit-box-shadow: 1px 1px 14px 0 rgba(18,38,63,.26);
    -moz-box-shadow: 1px 1px 14px 0 rgba(18,38,63,.26);
    box-shadow: 1px 1px 14px 0 rgba(18,38,63,.26);
    font-size: 16px;
  }
  .alert-success{
    border: 0;
    position: relative;
    padding: 0.95rem 1.25rem;
    border-radius: 1px;
    color: inherit;
    font-size: 16px;
    background-color: #fff;
    border-left: 5px solid green !important;
    -webkit-box-shadow: 1px 1px 14px 0 rgba(18,38,63,.26);
    -moz-box-shadow: 1px 1px 14px 0 rgba(18,38,63,.26);
    box-shadow: 1px 1px 14px 0 rgba(18,38,63,.26);
  }
  li i{
    font-size:18px !important;
  }

  .breadcrumb {
    display: flex;
    align-items: center;
    font-size: 14px;
    margin-bottom: 10px;
    border-radius: 5px;
    background-color: #fff;
    margin-bottom: 10px;
    -webkit-box-shadow: 2px 6px 15px 0 rgba(69,65,78,.1);
    -moz-box-shadow: 2px 6px 15px 0 rgba(69,65,78,.1);
    box-shadow: 2px 6px 15px 0 rgba(69,65,78,.1);
    border: 0
  }


  .breadcrumb-item {
    color: #999;
    text-decoration: none;
    display: flex;
    align-items: center;
  }

  .breadcrumb-item i {
    margin-right: 5px;
  }

  .breadcrumb-item:not(:last-child)::after {
    content: '/';
    margin: 0 5px;
    color: #999;
  }

  .breadcrumb-item.active {
    color: #333;
  }

</style>
<div class="vertical-menu">

  <div data-simplebar class="h-100">

      <!--- Sidemenu -->
      <div id="sidebar-menu" class="mm-active">
        @php $AccessModule = Auth::guard('admin')->user()->accountAccessModule @endphp
        @php $explode_1 = explode('|',$AccessModule);@endphp
          <ul class="metismenu list-unstyled" id="side-menu">
              <li class="menu-title mm-active"> Home </li>

              <li class="waves-effect @php echo ($file == 'dashboard') ? 'mm-active':'' @endphp">
                  @php
                    $startDate = \Carbon\Carbon::now()->subDays(7)->format('d-m-Y');
                    $endDate =  \Carbon\Carbon::now()->format('Y-m-d');
                    $urlParameter ="daterange_By=$startDate%20-%20$endDate";
                  @endphp
                  <a  href="{{url('admin/dashboard')}}"  class="waves-effect @php echo ($file == 'dashboard') ? 'active':'' @endphp">
                      <i class="dripicons-home"></i>
                      <span>Dashboard</span>
                  </a>
              </li>

               @php
                  $seg1 = $filename[1] ?? '';
                  $isRosary = in_array($seg1, ['rosary-categories', 'rosary-prayers']);
                  $isEdit = ($file == 'edit');
                @endphp

                <!-- Rosary Prayers -->
                <li class="{{ $isRosary ? 'mm-active' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="dripicons-heart"></i>
                        <span> Rosary Prayers</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">

                        <li>
                            <a href="{{ url('admin/rosary-categories') }}"
                              class="waves-effect {{ ($seg1 == 'rosary-categories' && $file == 'rosary-categories') ? 'active' : '' }}">
                                Category List
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('admin/rosary-categories/create') }}"
                              class="waves-effect {{ ($seg1 == 'rosary-categories' && $file == 'create') ? 'active' : '' }}">
                                Add Category
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('admin/rosary-prayers') }}"
                              class="waves-effect {{ ($seg1 == 'rosary-prayers' && $file == 'rosary-prayers') ? 'active' : '' }}">
                                Prayer List
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('admin/rosary-prayers/create') }}"
                              class="waves-effect {{ ($seg1 == 'rosary-prayers' && $file == 'create') ? 'active' : '' }}">
                                Add Prayer
                            </a>
                        </li>

                        @if($seg1 == 'rosary-categories' && $isEdit)
                            <li>
                                <a href="{{ url('admin/rosary-categories/' . ($filename[2] ?? '') . '/edit') }}" class="waves-effect active">
                                    Edit Category
                                </a>
                            </li>
                        @endif

                        @if($seg1 == 'rosary-prayers' && $isEdit)
                            <li>
                                <a href="{{ url('admin/rosary-prayers/' . ($filename[2] ?? '') . '/edit') }}" class="waves-effect active">
                                    Edit Prayer
                                </a>
                            </li>
                        @endif

                        {{-- <li>
                            <a href="{{ url('admin/rosary-api-list') }}"
                              class="waves-effect {{ ($file == 'rosary-api-list') ? 'active' : '' }}">
                                API List
                            </a>
                        </li> --}}
                    </ul>
                </li>
                              
              
              <li class="menu-title mm-active">Admin Menu</li>

              <li @php echo ($file == 'superadmin' || $file == 'admin_add_sub_admin' || $filename[1] == 'edit_admin') ? 'mm-active':'' @endphp">
                  <a href="javascript: void(0);" class="has-arrow waves-effect">
                      <i class=" dripicons-web"></i>
                      <span> Admin List </span>
                  </a>
                  <ul class="sub-menu" aria-expanded="false">
                      <li><a href="{{url('admin/superadmin')}}" class="waves-effect @php echo ($file == 'superadmin') ? 'active':'' @endphp" >Admin List</a></li>
                      <li><a  href="{{url('admin/admin_add_sub_admin')}}" class="waves-effect @php echo ($file == 'admin_add_sub_admin') ? 'active':'' @endphp" >Add Admin</a></li>
                      @if($filename[1] == 'edit_admin' )
                        <li>
                          <a href="/admin/edit_admin/{{$file}}" class="waves-effect @php echo ($filename[1] == 'edit_admin') ? 'active':'' @endphp">
                          Manage
                          </a>
                        </li>
                      @endif
                  </ul>
              </li>

              <li class="waves-effect @php echo ($file == 'dashboard') ? 'mm-active':'' @endphp">
                  <a href="{{route('admin.logout')}}" class="waves-effect">
                      <i class="dripicons-exit font-size-16 align-middle me-2"></i>
                      <span>Logout</span>
                  </a>
              </li>

          </ul>
      </div>
      <!-- Sidebar -->
  </div>
</div>
