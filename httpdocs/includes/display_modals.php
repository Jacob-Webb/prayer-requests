 <?php function display_prayer_form() { ?>

  <div class="modal fade" id="myModal" tabindex="1" role="dialog">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Prayer Request</h4>
              </div>
              <div class="modal-body">
                  <form id="request-form" action="receive_prayer_request.php" method="post" onsubmit="return confirm('We\'d love to see you in church!')">

                      <!-- Enter Prayer's personal information or remain anonymous if box is checked and user info fields will disappear -->
                      <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="anonymous" name="anonymous" value="anonymous">
                          <label class="form-check-label" for="anonymous">I would like to remain anonymous</label>
                          <div class="hide-if-active">
                              <div class="col-md-12" id="user-info">
                                  <label class="sr-only" for="user-first">First Name: </label>
                                  <input class="require-if-inactive" type="text" name="user-first" id="user-first"
                                      placeholder="First Name" tabindex="1" maxlength="30" data-require-pair="#anonymous">

                                  <label class="sr-only" for="user-last">Last Name: </label>
                                  <input class="require-if-inactive" type="text" name="user-last" id="user-last"
                                      placeholder="Last Name" tabindex="1" maxlength="30" data-require-pair="#anonymous">

                                  <label class="sr-only" for="email">Email Address: </label>
                                  <input class="require-if-inactive" type="email" id="email" name="email"
                                      placeholder="Email" tabindex="1" maxlength="30" data-require-pair="#anonymous">
                              </div> <!-- /.user-info -->
                          </div> <!-- /.hide-if-active -->
                          <div class="reveal-if-active">
                              <p>The church won't keep track of your personal information.</p>
                          </div> <!-- /.reveal-if-active -->
                      </div> <!-- form-check -->

                      <!-- Sets the Prayer's attendance value with checkbox value -->
                      <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="attend" name="attend" value="attend">
                          <label class="form-check-label" for="attend">I attend the church </label>
                      </div> <!-- /.form-check -->

                      <!-- Enter the Prayer's recipient if it is different than the users. If the box is checked the fields will become visible -->
                      <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="intercession" name="intercession" value="intercession">
                          <label class="form-check-label" for="intercession">This prayer is for someone else </label>
                          <div class="reveal-if-active">
                              <div id="recipient-name">
                                  <label class="sr-only" for="prayer-is-for">Person getting prayer: </label>
                                  <input class="require-if-active" type="text" name="prayer-is-for" id="prayer-is-for"
                                      placeholder="For:" tabindex="1" maxlength="30" data-require-pair="#intercession">
                              </div> <!-- /.recipient-name -->
                          </div> <!-- /.reveal-if-active -->
                      </div> <!-- form-check -->

                  
                      <!-- Enter one of the possible categories for Prayer -->
                      <div class="form-group">
                          <label class="sr-only" for="category">Category:</label>
                          <select class="custom-select custom-select-sm" name="category" id="category">
                              <option value="healing">Healing</option>
                              <option value="provision">Provision</option>
                              <option value="salvation">Salvation</option>
                              <option value="circumstances">Circumstances</option>
                          </select>
                      </div> <!-- /.form-group -->

                      <!-- Enter the actual Prayer request -->
                      <div class="form-group">
                          <label class="sr-only" for="prayer-request">Request:</label>
                          <textarea class="form-control" name="prayer-request" id="prayer-request" placeholder="Your Prayer Here" rows="5" maxlength="300" required></textarea>
                          <div id="characters-remaining">300</div>
                      </div> <!-- /.form-group -->

              </div> <!-- /.modal-body -->
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <!-- Pass is-admin=False when a prayer request is being added by a user.  -->
                  <input type="hidden" name="is-admin" value="False">
                  <button type="submit" class="btn btn-primary">Send Your Request</button>
              </div> <!-- /.modal-footer -->
              </form>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

 <?php } 
 
 function display_confirmation() {?>
     <div class="modal fade" id="confirmation_modal" tabindex="1" role="dialog">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Prayer Request</h4>
              </div>
              <div class="modal-body">
                  <form id="request-form" action="receive_prayer_request.php" method="post">

                      <!-- Enter Prayer's personal information or remain anonymous if box is checked and user info fields will disappear -->
                      <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="anonymous" name="anonymous" value="anonymous">
                          <label class="form-check-label" for="anonymous">I would like to remain anonymous</label>
                          <div class="hide-if-active">
                              <div class="col-md-12" id="user-info">
                                  <label class="sr-only" for="user-first">First Name: </label>
                                  <input class="require-if-inactive" type="text" name="user-first" id="user-first"
                                      placeholder="First Name" tabindex="1" maxlength="30" data-require-pair="#anonymous">

                                  <label class="sr-only" for="user-last">Last Name: </label>
                                  <input class="require-if-inactive" type="text" name="user-last" id="user-last"
                                      placeholder="Last Name" tabindex="1" maxlength="30" data-require-pair="#anonymous">

                                  <label class="sr-only" for="email">Email Address: </label>
                                  <input class="require-if-inactive" type="email" id="email" name="email"
                                      placeholder="Email" tabindex="1" maxlength="30" data-require-pair="#anonymous">
                              </div> <!-- /.user-info -->
                          </div> <!-- /.hide-if-active -->
                          <div class="reveal-if-active">
                              <p>The church won't keep track of your personal information.</p>
                          </div> <!-- /.reveal-if-active -->
                      </div> <!-- form-check -->

                      <!-- Sets the Prayer's attendance value with checkbox value -->
                      <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="attend" name="attend" value="attend">
                          <label class="form-check-label" for="attend">I attend the church </label>
                      </div> <!-- /.form-check -->

                      <!-- Enter the Prayer's recipient if it is different than the users. If the box is checked the fields will become visible -->
                      <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="intercession" name="intercession" value="intercession">
                          <label class="form-check-label" for="intercession">This prayer is for someone else </label>
                          <div class="reveal-if-active">
                              <div id="recipient-name">
                                  <label class="sr-only" for="prayer-is-for">Person getting prayer: </label>
                                  <input class="require-if-active" type="text" name="prayer-is-for" id="prayer-is-for"
                                      placeholder="For:" tabindex="1" maxlength="30" data-require-pair="#intercession">
                              </div> <!-- /.recipient-name -->
                          </div> <!-- /.reveal-if-active -->
                      </div> <!-- form-check -->

                  
                      <!-- Enter one of the possible categories for Prayer -->
                      <div class="form-group">
                          <label class="sr-only" for="category">Category:</label>
                          <select class="custom-select custom-select-sm" name="category" id="category">
                              <option value="healing">Healing</option>
                              <option value="provision">Provision</option>
                              <option value="salvation">Salvation</option>
                              <option value="circumstances">Circumstances</option>
                          </select>
                      </div> <!-- /.form-group -->

                      <!-- Enter the actual Prayer request -->
                      <div class="form-group">
                          <label class="sr-only" for="prayer-request">Request:</label>
                          <textarea class="form-control" name="prayer-request" id="prayer-request" placeholder="Your Prayer Here" rows="5" maxlength="300" required></textarea>
                          <div id="characters-remaining">300</div>
                      </div> <!-- /.form-group -->

              </div> <!-- /.modal-body -->
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <!-- Pass is-admin=False when a prayer request is being added by a user.  -->
                  <input type="hidden" name="is-admin" value="False">
                  <button type="submit" class="btn btn-primary">Send Your Request</button>
              </div> <!-- /.modal-footer -->
              </form>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

 <?php } ?>