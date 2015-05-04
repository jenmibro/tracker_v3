<?php $allowEdit = User::canUpdate($user->role); ?>

<div class='modal-header'>
	<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'><i class='fa fa-times-circle'></i></span></button>
	<h4 class='modal-title'>Editing <strong><?php echo $member->rank . " " . $member->forum_name ?></strong></h4>
</div>
<form id='edit-form'>
	<div class='modal-body' style='overflow-y: scroll; max-height: 400px;'>

		<div class='message alert' style='display: none;'></div>

		<input type='hidden' id='uid' name='uid' value='<?php echo $member->id ?>' />
		<input type='hidden' id='cur_plt' name='cur_plt' value='<?php echo $member->platoon_id ?>' />
		<input type='hidden' id='cur_sqd' name='cur_sqd' value='<?php echo $member->squad_leader_id ?>' />
		<input type='hidden' id='cur_pos' name='cur_pos' value='<?php echo $member->position_id ?>' />

		<div class="tabbable">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#profile" data-toggle="tab"><i class="fa fa-user text-muted fa-lg"></i> Member Profile</a></li>
				<li><a href="#gameinfo" data-toggle="tab"><i class="fa fa-gamepad text-muted fa-lg"></i> Game Info</a></li>
				<li><a href="#divinfo" data-toggle="tab"><i class="fa fa-cog text-muted fa-lg"></i> Division Info</a></li>
				<li><a href="#aliasinfo" data-toggle="tab"><i class="fa fa-users text-muted fa-lg"></i> Aliases</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="profile">
					<div class="margin-top-20"></div>
					<div class='form-group'>
						<label for='forum_name' class='control-label'>Forum Name</label>
						<input type="text" class="form-control" id="forum_name" value="<?php echo $member->forum_name ?>" <?php echo ($user->role == 1) ? "disabled" : NULL; ?>>
					</div>

					<div class='form-group'>
						<label for='member_id' class='control-label'>Forum ID</label>
						<input type='number' class='form-control' id='member_id' value='<?php echo $member->member_id ?>'>
					</div>
					<div class='form-group'>
						<label for='recruiter' class='control-label'>Recruiter ID</label>
						<input type='number' class='form-control' id='recruiter' value='<?php echo $member->recruiter ?>'>
					</div>
					<div class="margin-top-20"></div>

				</div>
				<div class="tab-pane" id="divinfo">
					<div class="margin-top-20"></div>
					
					<div class='form-group platoon-group' style='display: <?php echo $allowEdit->pltField ?>'>
						<label for='platoon' class='control-label'>Platoon</label>
						<select name='platoon' id='platoon' class='form-control'>
							<?php if (count(Platoon::countPlatoons())) : ?>
								<?php foreach($platoons as $platoon) : ?>
									<option value='<?php echo $platoon->id ?>'><?php echo $platoon->name ?></option>
								<?php endforeach; ?>
							<?php else : ?>
								<option>No platoons exist.</option>
							<?php endif; ?>
							<option value='0' selected>None (General Sergeant or Division Leader)</option>
						</select>
					</div>

					<div class='form-group sqdldr-group' style='display: <?php echo $allowEdit->sqdField ?>'>
						<label for='sqdldr' class='control-label'>Squad Leader</label>
						<select name='sqdldr' id='sqdldr' class='form-control'>
							<?php if (count(Division::countSquadLeaders($member->game_id))) : ?>
								<?php foreach($squadleadersArray as $squadLeader) : ?>
									<option value='<?php echo $squadLeader->member_id ?>'><?php echo $squadLeader->forum_name ?> - <?php echo $squadLeader->platoon_name ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
							<option value='0' selected>None (Gen Pop or Division Leader)</option>
						</select>
					</div>

					<div class='form-group position-group' style='display: <?php echo $allowEdit->posField ?>'>
						<label for='position' class='control-label'>Position</label>
						<select name='position' id='position' class='form-control'>
							<?php foreach ($positionsArray as $position) : ?>
								<option value='<?php echo $position->id ?>'><?php echo $position->desc ?></option>
							<?php endforeach; ?>
						</select> 
					</div>

					<div class="margin-top-20"></div>

				</div>

				<div class="tab-pane" id="gameinfo">
					<div class="margin-top-20"></div>
					<?php if (count(SubGame::count($member->game_id))): ?>
						<div class="form-group game-group">
							<label for='platoon' class='control-label'>Games Played</label><br />
							<select id="games" multiple="multiple">
								<?php foreach (SubGame::get($member->game_id) as $game): ?>
									<?php $selected = (MemberGame::plays($member->id, $game->id)) ? "selected='selected'" : ""; ?> 
									<option value="<?php echo $game->id ?>" <?php echo $selected ?>><?php echo $game->full_name ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					<?php endif; ?>
					<div class="margin-top-50"></div>
				</div>				
				<div class="tab-pane" id="aliasinfo">
					<div class="margin-top-20"></div>
					<div class='form-group battlelog-group'>
						<label for='battlelog' class='control-label'>Battlelog Name</label>
						<input type='text' class='form-control' id='battlelog' value='<?php echo $member->battlelog_name ?>'>
					</div>
					<div class="margin-top-20"></div>
				</div>
			</div>
		</div>
	</div>


</div>
<div class='modal-footer'>
	<button type='button' class='btn btn-default' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>Cancel</span></button><button type='submit' class='btn btn-success'>Save Info</button> 
</form>
</div>

<script src='assets/js/view.js'></script>