<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3>User Profile</h3>
			</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_content">

						<!-- LEFT PART -->
						<div class="col-md-3 col-sm-3 col-xs-12 profile_left">

							<div class="profile_img">

								<!-- end of image cropping -->
								<div id="crop-avatar">
									<!-- Current avatar -->
									<img class="img-responsive avatar-view" src=<?php echo $relative_path . $_SESSION['face_pic'] ?> alt="Avatar" title="Change the avatar">

  								<!-- Loading state -->
									<div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
								</div>
								<!-- end of image cropping -->

							</div>
							<h3>Samuel Doe</h3>

							<ul class="list-unstyled user_data">
								<li>
									<i class="fa fa-briefcase user-profile-icon"></i> 
									<?php
										if ($_SESSION['right'] == 'admin')
										{
											$title = 'an administrator';
										}
										elseif ($_SESSION['right'] == 'user')
										{
											$title = 'a regular user';
										}
										else
										{
											$title = 'a guest';
										}

										echo 'Your are '.$title;
									?>
								</li>
							</ul>

							<a class="btn btn-success col-xs-6" id="editProfileButton">
								<i class="fa fa-edit m-right-xs"></i>
								Edit Profile
							</a>
							<a class="btn btn-info col-xs-6" id="editPasswordButton">
								<i class="fa fa-edit m-right-xs"></i>
								Edit Password
							</a>
							<br />

						</div>

						<!-- CENTRAL AND RIGHT PART -->

						<!-- Nothing for the moment -->

						<!-- EDIT PASSWORD MODAL PART -->
						<div class="modal fade" id="editPasswordModal" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
						    <div class="modal-dialog">
						      <div class="modal-content">
						        <div class="modal-header">
						          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
						          <h4 id="modalTitle" class="modal-title">Edit Password</h4>
						        </div>
						        <div class="modal-body">
						        	<form method="post" action="php/changePassword_post.php">
						            <!-- Text input-->
						            <div class="form-group">
						              <label class="col-md-4 control-label" for="password" >Password</label>  
						              <input id="editPasswordForm" name="Name" placeholder="ex: SailingTest" class="form-control input-md" required="1" type="text" >
						              <span class="help-block">Write your new password.</span>  
						            </div>

						            <!-- Text input-->
						            <div class="form-group">
						              <label class="col-md-4 control-label" for="Confirmation" >Corfirmation</label>  
						              <input id="editPasswordConfirmation" name="Name" placeholder="ex: SailingTest" class="form-control input-md" required="1" type="text" >
						              <span class="help-block">Please confirm your new password.</span>  
						            </div>
						          </form>
						        </div>
						        <div class="modal-footer ">
						            <button type="button" class="btn btn-primary myfooter" id="cancelEditPasswordButton">Cancel</button>
						            <button type="button" class="btn btn-success myfooter" id="confirmEditPasswordButton">Edit Password</button>
						        </div>
						      </div>
						    </div>
						</div>

						<!-- Cropping modal -->
						<div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<form class="avatar-form" action="crop.php" enctype="multipart/form-data" method="post">
										<div class="modal-header">
											<button class="close" data-dismiss="modal" type="button">&times;</button>
											<h4 class="modal-title" id="avatar-modal-label">Change Avatar</h4>
										</div>
										<div class="modal-body">
											<div class="avatar-body">

												<!-- Upload image and data -->
												<div class="avatar-upload">
													<input class="avatar-src" name="avatar_src" type="hidden">
													<input class="avatar-data" name="avatar_data" type="hidden">
													<label for="avatarInput">Local upload</label>
													<input class="avatar-input" id="avatarInput" name="avatar_file" type="file">
												</div>

												<!-- Crop and preview -->
												<div class="row">
													<div class="col-md-9">
														<div class="avatar-wrapper"></div>
													</div>
													<div class="col-md-3">
														<div class="avatar-preview preview-lg"></div>
														<div class="avatar-preview preview-md"></div>
														<div class="avatar-preview preview-sm"></div>
													</div>
												</div>

												<div class="row avatar-btns">
													<div class="col-md-9">
														<div class="btn-group">
															<button class="btn btn-primary" data-method="rotate" data-option="-90" type="button" title="Rotate -90 degrees">Rotate Left</button>
															<button class="btn btn-primary" data-method="rotate" data-option="-15" type="button">-15deg</button>
															<button class="btn btn-primary" data-method="rotate" data-option="-30" type="button">-30deg</button>
															<button class="btn btn-primary" data-method="rotate" data-option="-45" type="button">-45deg</button>
														</div>
														<div class="btn-group">
															<button class="btn btn-primary" data-method="rotate" data-option="90" type="button" title="Rotate 90 degrees">Rotate Right</button>
															<button class="btn btn-primary" data-method="rotate" data-option="15" type="button">15deg</button>
															<button class="btn btn-primary" data-method="rotate" data-option="30" type="button">30deg</button>
															<button class="btn btn-primary" data-method="rotate" data-option="45" type="button">45deg</button>
														</div>
													</div>
													<div class="col-md-3">
														<button class="btn btn-primary btn-block avatar-save" type="submit">Done</button>
													</div>
												</div>
											</div>
										</div>
										<!-- <div class="modal-footer">
																			<button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
																		</div> -->
									</form>
								</div>
							</div>
						</div>
						<!-- /.modal -->


						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>