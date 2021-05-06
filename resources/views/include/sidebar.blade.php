<li @if($menu == "dashboard") class="active" @endif>
	<a href="{{ URL('admin/dashboard') }}">
		<i class="fa fa-dashboard"></i> <span>Dashboard</span>
	</a>
</li>

@can('user')
<li @if($menu == "user") class="active" @endif>
	<a href="{{ URL('admin/user/all_user') }}">
		<i class="fa fa-dashboard"></i> <span>Users</span>
	</a>
</li>
@endcan

@can('user-profile-pending')
<li @if($menu == "user-profile-pending") class="active" @endif>
	<a href="{{ URL('admin/user/profile/pending') }}">
		<i class="fa fa-dashboard"></i> <span>User Profile Pending</span>
	</a>
</li>
@endcan

@can('contract')
<li @if($menu == "contract") class="active" @endif>
	<a href="{{ URL('admin/contract/all_contract') }}">
		<i class="fa fa-dashboard"></i> <span>Contract</span>
	</a>
</li>
@endcan

@can('settings')
<li class="treeview @if($menu == "settings") menu-open active @endif">
	<a href="#">
		<i class="fa fa-dashboard"></i> <span>Settings</span>
			<span class="pull-right-container">
			<i class="fa fa-angle-left pull-right"></i>
		</span>
	</a>
	<ul class="treeview-menu">
		<li @if($sub_menu == "slider") class="active" @endif>
			<a href="{{ url('admin/settings/slider_all') }}">
				<i class="fa fa-circle-o"></i> Slider
			</a>
		</li>
		<li @if($sub_menu == "news") class="active" @endif>
			<a href="{{ url('admin/settings/news_all') }}">
				<i class="fa fa-circle-o"></i> News
			</a>
		</li>
		<li @if($sub_menu == "countries") class="active" @endif>
			<a href="{{ url('admin/settings/countries_all') }}">
				<i class="fa fa-circle-o"></i> Country
			</a>
		</li>
	</ul>
</li>
@endcan

@can('teams')
<li class="treeview @if($menu == "teams") menu-open active @endif">
	<a href="#">
		<i class="fa fa-dashboard"></i> <span>Teams</span>
			<span class="pull-right-container">
			<i class="fa fa-angle-left pull-right"></i>
		</span>
	</a>
	<ul class="treeview-menu">
		<li @if($sub_menu == "all_teams") class="active" @endif>
			<a href="{{ url('admin/teams/all') }}">
				<i class="fa fa-circle-o"></i> All Teams
			</a>
		</li>
		<li @if($sub_menu == "assign_teams") class="active" @endif>
			<a href="{{ url('admin/teams/assign_teams') }}">
				<i class="fa fa-circle-o"></i> Assign Team
			</a>
		</li>
	</ul>
</li>
@endcan

<!-- <li class="treeview @if($menu == "leagues") menu-open active @endif">
	<a href="#">
		<i class="fa fa-dashboard"></i> <span>VPC System</span>
			<span class="pull-right-container">
			<i class="fa fa-angle-left pull-right"></i>
		</span>
	</a>
	<ul class="treeview-menu">
		<li @if($sub_menu == "all_league") class="active" @endif>
			<a href="{{ url('admin/leagues/all') }}">
				<i class="fa fa-circle-o"></i> All Leagues
			</a>
		</li>
	</ul>
</li> -->
@can('vpc-system')
<li @if($menu == "vpc_system") class="active" @endif>
	<a href="{{ URL('admin/vpc_system/all') }}">
		<i class="fa fa-dashboard"></i> <span>VPC System</span>
	</a>
</li>
@endcan

@can('games')
<li @if($menu == "games") class="active" @endif>
	<a href="{{ URL('admin/games/all_game') }}">
		<i class="fa fa-dashboard"></i> <span>Games</span>
	</a>
</li>
@endcan

@can('modes')
<li @if($menu == "modes") class="active" @endif>
	<a href="{{ URL('admin/modes/all_mode') }}">
		<i class="fa fa-dashboard"></i> <span>Modes</span>
	</a>
</li>
@endcan

@can('platforms')
<li @if($menu == "plateforms") class="active" @endif>
	<a href="{{ URL('admin/plateforms/all_plateforms') }}">
		<i class="fa fa-dashboard"></i> <span>Platforms</span>
	</a>
</li>
@endcan

@can('all-tournament')
<li @if($menu == "tournament") class="active" @endif>
	<a href="{{ URL('admin/tournament/all_tournament') }}">
		<i class="fa fa-dashboard"></i> <span>All Tournament</span>
	</a>
</li>
@endcan

@can('league')
<li class="treeview @if($menu == "league") menu-open active @endif">
	<a href="#">
		<i class="fa fa-dashboard"></i> <span>League</span>
			<span class="pull-right-container">
			<i class="fa fa-angle-left pull-right"></i>
		</span>
	</a>
	<ul class="treeview-menu">
		<li @if($sub_menu == "aleague") class="active" @endif>
			<a href="{{ URL('admin/league/all_league') }}">
				<i class="fa fa-dashboard"></i> <span>All Leagues</span>
			</a>
		</li>
		<li @if($sub_menu == "match") class="active" @endif>
			<a href="{{ URL('admin/match/all_match') }}">
				<i class="fa fa-dashboard"></i> <span>All Matches</span>
			</a>
		</li>
	</ul>
</li>
@endcan

@can('division')
<li @if($menu == "division") class="active" @endif>
	<a href="{{ URL('admin/division/all_division') }}">
		<i class="fa fa-dashboard"></i> <span>Divisions</span>
	</a>
</li>
@endcan

@can('position')
<li @if($menu == "position") class="active" @endif>
	<a href="{{ URL('admin/position/all_position') }}">
		<i class="fa fa-dashboard"></i> <span>Position</span>
	</a>
</li>
@endcan

@can('all-statistic')
<li @if($menu == "statistic") class="active" @endif>
	<a href="{{ URL('admin/statistic/all_statistic') }}">
		<i class="fa fa-dashboard"></i> <span>All Statistic</span>
	</a>
</li>
@endcan

@can('trophy')
<li class="treeview @if($menu == "awards") menu-open active @endif">
	<a href="#">
		<i class="fa fa-dashboard"></i> <span>Trophy</span>
			<span class="pull-right-container">
			<i class="fa fa-angle-left pull-right"></i>
		</span>
	</a>
	<ul class="treeview-menu">
		<li @if($sub_menu == "cawards") class="active" @endif>
			<a href="{{ URL('admin/awards/all_awards') }}">
				<i class="fa fa-dashboard"></i> <span>All Trophy</span>
			</a>
		</li>
		<li @if($sub_menu == "assign_awards") class="active" @endif>
			<a href="{{ URL('admin/awards/all_assign') }}">
				<i class="fa fa-dashboard"></i> <span>Assign Trophy</span>
			</a>
		</li>
	</ul>
</li>
@endcan

@can('medals')
<li class="treeview @if($menu == "medals") menu-open active @endif">
	<a href="#">
		<i class="fa fa-dashboard"></i> <span>Medals</span>
			<span class="pull-right-container">
			<i class="fa fa-angle-left pull-right"></i>
		</span>
	</a>
	<ul class="treeview-menu">
		<li @if($sub_menu == "cmedals") class="active" @endif>
			<a href="{{ URL('admin/medals/all_medals') }}">
				<i class="fa fa-dashboard"></i> <span>All Medals</span>
			</a>
		</li>
		<li @if($sub_menu == "assign_medals") class="active" @endif>
			<a href="{{ URL('admin/medals/all_assign_medal') }}">
				<i class="fa fa-dashboard"></i> <span>Assign Medals</span>
			</a>
		</li>
	</ul>
</li>
@endcan

@can('leaderboard')
<li @if($menu == "leaderboard") class="active" @endif>
	<a href="{{ URL('admin/leaderboard/all_leaderboard') }}">
		<i class="fa fa-dashboard"></i> <span>Leaderboard</span>
	</a>
</li>
@endcan

@can('role-management')
<li class="treeview @if($menu == "role-management") menu-open active @endif">
	<a href="#">
		<i class="fa fa-dashboard"></i> <span>Role Management</span>
			<span class="pull-right-container">
			<i class="fa fa-angle-left pull-right"></i>
		</span>
	</a>
	<ul class="treeview-menu">
		<li @if($sub_menu == "role") class="active" @endif>
			<a href="{{ URL('admin/role/view') }}">
				<i class="fa fa-dashboard"></i> <span>Role</span>
			</a>
		</li>
		<!-- <li @if($sub_menu == "permission") class="active" @endif>
			<a href="{{ URL('admin/permission/view') }}">
				<i class="fa fa-dashboard"></i> <span>Permission</span>
			</a>
		</li> -->
		<li @if($sub_menu == "permission-assign") class="active" @endif>
			<a href="{{ URL('admin/permission/assign/view') }}">
				<i class="fa fa-dashboard"></i> <span>Permission Assign</span>
			</a>
		</li>
	</ul>
</li>
@endcan

@can('leaderboard')
<li @if($menu == "super_users") class="active" @endif>
	<a href="{{ URL('admin/superusers/all') }}">
		<i class="fa fa-dashboard"></i> <span>Super Users</span>
	</a>
</li>
@endcan