$(document).ready(function()
{	

	$.ajaxSetup({
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')	},
		cache: false
	});

	//Common Variables
	var windowHeight = window.innerHeight;
	var documentHeight = document.body.clientHeight;
	var screenHeight = screen.height;
	
	// Remove error and success messages
	if($(".flashMessage").length > 0) {
		setTimeout(function() {
			$(".flashMessage").fadeOut();
		}, 6000);
	} else if($(".errorMessage").length > 0) {
		setTimeout(function() {
			$(".errorMessage").fadeOut();
		}, 6000);
	}
	
	// Animations initialization
	new WOW().init();
	
	// Initialize MDB select
	$('.mdb-select').material_select();
	
	// Initialize Datetimepicker
	$('.datetimepicker').pickadate({
		// Escape any “rule” characters with an exclamation mark (!).
		format: 'mm/dd/yyyy',
		min: new Date(1970,1,01),
	});
	
	// Initialize timepicker
	$('.timepicker').pickatime({
		// 12 or 24 hour 
		twelvehour: true,
		autoclose: true,
		default: '18:00',
	});
	
	// Dropdown Init
	$('.dropdown-toggle').dropdown();
	
	// Table Adjustments
	$('#family_members_table').DataTable({
		"scrollX": true,
	});
	$('.dataTables_length').addClass('bs-select');


	// SideNav Button Initialization
	$(".button-collapse").sideNav({
		edge: 'left', // Choose the horizontal origin
		closeOnClick: true // Closes side-nav on <a> clicks, useful for Angular/Meteor
	});
	// SideNav Scrollbar Initialization
	var sideNavScrollbar = document.querySelector('.custom-scrollbar');
	Ps.initialize(sideNavScrollbar);
	
	// Delete duplicate family member account
	$('body').on('click', '.deleteDupe', function() {
		deleteDupe($(this).children().val());
	});
	
	// Keep the potential duplicate family member account
	$('body').on('click', '.keepDupe', function() {
		keepDupe($(this).children().val());
	});
	
	// Add new committee member row
	$('body').on('click', '.addCommitteeMember', function() {
		var newCommitteeRow = $('.committeeRow').clone();
		
		if($('.emptyCommittee').length > 0) {
			$('.emptyCommittee').slideUp();
		}
		
		$(newCommitteeRow).find('select').removeAttr('disabled');
		$(newCommitteeRow).removeClass('committeeRow')
			.removeAttr('hidden')
			.insertBefore('.committeeRow');
		$('.committeeRow').prev().find('select').focus();
	});
	
	// Add new reunion event row
	$('body').on('click', '.addReunionEvent', function() {
		var newEventRow = $('.reunionEventRow').clone();
		
		if($('.emptyEvents').length > 0) {
			$('.emptyEvents').slideUp();
		}
		
		$(newEventRow).find('input, textarea').removeAttr('disabled');
		$(newEventRow).removeClass('reunionEventRow')
			.removeAttr('hidden')
			.insertBefore('.reunionEventRow');
		$('.reunionEventRow').prev()
			.find('.datetimepicker')
			.pickadate({
				timepicker:false,
				format:'mm/dd/yyyy',
			});
	});

	// Remove new committee member row
	$('body').on('click', '.removeCommitteeMember', function(e) {
		var committeeMemberRow = $(this).parent().parent();
		$(committeeMemberRow).slideUp(function() {
			$(committeeMemberRow).remove();
		});
	});
	
	
	// Remove new event row
	$('body').on('click', '.removeReunionEventRow', function(e) {
		var eventRow = $(this).parent().parent();
		$(eventRow).slideUp(function() {
			$(eventRow).remove();
		});
	});
	
	// Remove new household member row
	$('body').on('click', '.removeHHMember', function(e) {
		var HHMemberRow = $(this).parent().parent();
		$(HHMemberRow).slideUp(function() {
			$(HHMemberRow).remove();
		});
	});
	
	// Button toggle switch
	$('body').on("click", "button", function(e) {
		if(!$(this).hasClass('btn-primary') || !$(this).hasClass('btn-danger')) {
			if($(this).children().val() == "Y") {
				$(this).addClass('active btn-success').children().attr("checked", true);
				$(this).siblings().addClass('btn-secondary').removeClass('active btn-danger').children().removeAttr("checked");
				
				// If this is the contacts page, toggle the addresses select div visibility
				if($('.tenantProp').length > 0) {
					$('.tenantProp').slideDown();
				}
			} else if($(this).children().val() == 'N') {
				$(this).addClass('active btn-danger').children().attr("checked", true);
				$(this).siblings().addClass('btn-secondary').removeClass('active btn-success').children().removeAttr("checked");
				
				// If this is the contacts page, toggle the addresses select div visibility
				if($('.tenantProp').length > 0) {
					$('.tenantProp').slideUp();
				}
			}
		}	
	});

	// Change href location when select option is changed
	// on registration create page
	$('body').on('change', '.createRegSelect', function(e) {
		// Get Selected Member ID
		var selectedMember = $(this).find('option:selected').val();
		
		// Change the link address
		$('.createRegSelectLink').attr('href', '/members/'+ selectedMember +'/edit')
	});
	
	// Change the default number of attending adults to 1
	$('#registration_modal').on('show.bs.modal', function() {
		$('input#attending_adult').val('1').change();
	});
	
	// Toggle descent options
	$("body").on("click", ".descentInput", function(e) {
		e.preventDefault();
		
		if($(this).hasClass('active')) {
			
			$(this).toggleClass("btn-outline-success btn-success active").children().removeAttr('checked');
			
		} else {
			
			$(this).toggleClass("btn-outline-success btn-success active").children().attr('checked', true);

			if($(this).siblings().hasClass('active')) {
				
				$(this).siblings().toggleClass("btn-outline-success btn-success active").children().removeAttr('checked');
				
			}
		}

	});
	
	// Add Household Member Row
	$('body').on('click', '.addHHMember', function() {
		var hhMemberRow = $('.hhMemberRow').clone();
		$(hhMemberRow).addClass('d-flex').insertBefore($('.hhMemberRow')).removeClass('hidden hhMemberRow').find('select').focus();
	});
	
	// Add Child Member Row
	$('body').on('click', '.addChildrenRow', function() {
		var childrenRow = $('.childrenRow').clone();
		$(childrenRow).addClass('d-flex').insertBefore($('.childrenRow')).removeClass('hidden childrenRow').find('select').focus();
	});
	
	// Add Sibling Member Row
	$('body').on('click', '.addSiblingRow', function() {
		var siblingRow = $('.siblingRow').clone();
		$(siblingRow).addClass('d-flex').insertBefore($('.siblingRow')).removeClass('hidden siblingRow').find('select').focus();
	});
	
	// Add name rows for adults and edit the total amount due
	$("body").on("change", "#attending_adult", function(e) {
		var count = $("#attending_adult").val();
		var totalNewRows = $('.attending_adult_row').not('#attending_adult_row_default');
		
		// Remove new rows if any exist
		$(totalNewRows).remove();
		
		// Adjust rows to amount entered
		for(var x=0; x < count; x++) {
			// Get hidden youth row and insert if before hidden default row
			var newAdultRow = $("#attending_adult_row_default").clone();
			$(newAdultRow).removeAttr('id').find('input, select').removeAttr('disabled');
			$(newAdultRow).insertBefore($("#attending_adult_row_default"));
			$('.attending_adult_name').first().val($('input#firstname').val());
		}
	});
	
	//Add name rows for youths and edit the total amount due
	$("body").on("change", "#attending_youth", function(e) {
		var count = $("#attending_youth").val();
		var totalNewRows = $('.attending_youth_row').not('#attending_youth_row_default');
		
		// Remove new rows if any exist
		$(totalNewRows).remove();
		
		// Adjust rows to amount entered
		for(var x=0; x < count; x++) {
			// Get hidden youth row and insert if before hidden default row
			var newAdultRow = $("#attending_youth_row_default").clone();
			$(newAdultRow).removeAttr('id').find('input, select').removeAttr('disabled');
			$(newAdultRow).insertBefore($("#attending_youth_row_default"));
		}
	});
	
	//Add name rows for children
	$("body").on("change", "#attending_children", function(e) {
		var count = $("#attending_children").val();
		var totalNewRows = $('.attending_children_row').not('#attending_children_row_default');
		
		// Remove new rows if any exist
		$(totalNewRows).remove();
		
		// Adjust rows to amount entered
		for(var x=0; x < count; x++) {
			// Get hidden youth row and insert if before hidden default row
			var newAdultRow = $("#attending_children_row_default").clone();
			$(newAdultRow).removeAttr('id').find('input, select').removeAttr('disabled');
			$(newAdultRow).insertBefore($("#attending_children_row_default"));
		}
	});
	
	//
	$('body').on('click', '#accordion .card h3', function(e) {
		var headerOne = $('#collapseOne');
		var headerTwo = $('#collapseTwo');
		
		$(headerOne).on('hide.bs.collapse', function () {
			$(this).find('select').attr('disabled', true);
		});
		
		$(headerOne).on('show.bs.collapse', function () {
			$(this).find('select').removeAttr('disabled');
		});
		
		$(headerTwo).on('hide.bs.collapse', function () {
			$(this).find('input').attr('disabled', true);
		});
		
		$(headerTwo).on('show.bs.collapse', function () {
			$(this).find('input, select').removeAttr('disabled');
		});
	});
	
//Add scroll to the top button
	$(window).scroll(function()	{
		var containerHeight = $("#container").innerHeight();
		var containerHeight90 = (Number(window.pageYOffset) + Number(window.innerHeight));
		if(window.pageYOffset >= 300) {
			if(containerHeight90 >= (containerHeight - 200)) {
				$("#scroll_to_top").fadeOut("slow");
			} else {
				$("#scroll_to_top").show("slow");
			}
		}	
		if(window.pageYOffset < 300){
			$("#scroll_to_top").hide("slow");
		}
	});
	
//Scroll to the top of the page
	$("body").on("click", "#scroll_to_top", function(e)
	{
		var body = document.body;
		$(body).animate({scrollTop:$(body).offset().top}, "slow");
	});
	
//Remove Error Border
	$("body").on("focus", "#registration_modal input", function(e)
	{
		$(this).removeClass("error_border");
		$("#errors_modal").fadeOut(function(){ $("errors_modal_contentP").empty(); });
	});
	
	//Search option box
	$(".memberFilter ").keyup(function(e){
		startSearch($(".memberFilter ").val());
	});
	
	// Add form to confirm delete modal for deleting carousel images
	$('body').on('click', '.deleteCarousel', function() {
		var copiedImage = $(this).parents('.card').find('img').clone();
		var removeIndex = $('#pictureConfirmDelete form').attr('action').lastIndexOf('/') + 1;
		var formAction = $('#pictureConfirmDelete form').attr('action').slice(0, removeIndex);
		formAction = formAction + $(this).parents('.card').find('input').val();

		$('#pictureConfirmDelete .modal-body').empty();
		
		copiedImage.appendTo($('#pictureConfirmDelete .modal-body'));
		$('#pictureConfirmDelete form').attr('action', formAction);
	});
	
	// Show button to submit new reunion background after file has been added
	$('body').on('change', 'input[name="new_reunion_background"]', function() {
		$('.reunionBgrdImg').parent().addClass('flipInX').removeAttr('style');
	});
	
	// Show button to submit new reunion hotel image after file has been added
	$('body').on('change', 'input[name="new_hotel_picture"]', function() {
		$('.reunionHotelImage').parent().addClass('flipInX').removeAttr('style');
	});
	
	// Upload new image for the current reunion
	$('body').on('click', 'button.reunionBgrdImg', function() {
		event.preventDefault();
		
		var formData = new FormData();
		var reunion_id = $(this).parent().prev().find('input[type="number"]').val();
		formData.append("photo", document.getElementById('new_reunion_background').files[0]);

		$.ajax({
			url: "/reunion_image_add/" + reunion_id,
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			cache: false,
			xhr: function() {
				var xhr = new XMLHttpRequest();
				
				xhr.upload.addEventListener('progress', function(e) {
					var progressbar = Math.round((e.loaded/e.total) * 100);
					$('#progress_modal').modal('show');
					$('#pro').css('width', progressbar + '%').text(progressbar + '%');
				});
				
				return xhr;
			},
			
			success: function(data) {
				// Display a success toast
				toastr.success(data);
				
				$(".reunionBgrdWrapper").load("/reunions/" + reunion_id + "/edit .reunionBgrdDiv", function(responseTxt, statusTxt, xhr) {
					$('#progress_modal').modal('toggle');
				});
			},
		});
		
		return false;
	});
	
	// Upload new image for the current reunions hotel
	$('body').on('click', 'button.reunionHotelImage', function() {
		event.preventDefault();
		
		var formData = new FormData();
		var reunion_id = $(this).parent().prev().find('input[type="number"]').val();
		formData.append("photo", document.getElementById('new_hotel_picture').files[0]);
		formData.append("reunion", reunion_id);

		$.ajax({
			url: "/reunion_hotel_image_add/" + reunion_id,
			method: "POST",
			data: formData,
			contentType: false,
			processData: false,
			cache: false,
			xhr: function() {
				var xhr = new XMLHttpRequest();
				
				xhr.upload.addEventListener('progress', function(e) {
					var progressbar = Math.round((e.loaded/e.total) * 100);
					$('#progress_modal').modal('show');
					$('#pro').css('width', progressbar + '%').text(progressbar + '%');
				});
				
				return xhr;
			},
			
			success: function(data) {
				// Display a success toast
				toastr.success(data);
				
				$(".reunionHotelImageWrapper").load("/reunions/" + reunion_id + "/edit .reunionHotelImageDiv", function(responseTxt, statusTxt, xhr) {
					$('#progress_modal').modal('toggle');
				});
			},
		});
		
		return false;
	});
});

// Filter members with search input
// Check text to see if it matches the search criteria being entered
function startSearch(searchVal) {
	var membersTable = $('table.table tbody tr');
	var searchCriteria = searchVal.toLowerCase();
	var foundResults = 0;
	$(membersTable).removeClass("matches");
	$('.noSearchResults').remove();
	
	if(searchCriteria != "") {
		$(membersTable).each(function(event){
			var dataString = $(this).find('.nameSearch').text().toLowerCase();
			
			if(dataString.includes(searchCriteria)) {
				$(this).addClass("matches");
				$(this).show();
				foundResults++;
			} else if(!dataString.includes(searchCriteria)) {
				$(this).hide();
			}
		});
		
		// If all rows are hidden, then add a row saying no results found
		if(foundResults == 0) {
			$('<tr class="noSearchResults"><td>No Results Found</td></tr>').appendTo($('table.table tbody'));
		}
	}
}

// Check for empty boxes on registration form
function emptyInputCheck() {
	// event.preventDefault();
	var errors = true;
	
	if($('.attending_adult_row').not('#attending_adult_row_default').length > 0) {
		$('.attending_adult_row').not('#attending_adult_row_default').each(function(e) {
			var thisName = $(this).find('input');
			var thisShirt = $(this).find('select option:selected');
			var thisSelect = $(this).find('select');
			var selectError = "<span class='d-block text-center text-danger'>Select A Shirt Size</span>";
			var inputError = "<span class='d-block text-center text-danger'>Enter A Name</span>";
			
			if($(thisName).val() == '') {
				$(thisName).addClass('error_border');
				$(inputError).insertAfter(thisName);
				errors = false;
			}
			
			if($(thisShirt).val() == 'blank') {
				$(thisSelect).addClass('error_border');
				$(selectError).insertAfter(thisSelect);
				errors = false;
			}
		});
	}
	
	if($('.attending_youth_row').not('#attending_youth_row_default').length > 0) {
		$('.attending_youth_row').not('#attending_youth_row_default').each(function(e) {
			var thisName = $(this).find('input');
			var thisShirt = $(this).find('select option:selected');
			var thisSelect = $(this).find('select');
			
			if($(thisName).val() == '') {
				$(thisName).addClass('error_border');
				errors = false;
			}
			
			if($(thisShirt).val() == 'blank') {
				$(thisSelect).addClass('error_border');
				errors = false;
			}
		});
	}
	
	if($('.attending_children_row').not('#attending_children_row_default').length > 0) {
		
		$('.attending_children_row').not('#attending_children_row_default').each(function(e) {
			
			var thisName = $(this).find('input');
			var thisShirt = $(this).find('select option:selected');
			var thisSelect = $(this).find('select');
			
			if($(thisName).val() == '') {
				$(thisName).addClass('error_border');
				errors = false;
			}
			
			if($(thisShirt).val() == 'blank') {
				$(thisSelect).addClass('error_border');
				errors = false;
			}
			
		});
		
	}
	
	if(errors === false) {
		
		return false;
		
	} else {
		
		// Remove Registration Modal First
		$('#registration_modal').modal('hide').ready(function() {
			// Bring up loading modal when form is submitted on registration page
			$('.loadingSpinner').find('p').text('Submitting Registration Information');
			$('.loadingSpinner').modal('show');
		});
		
		$('input#total_adult, input#total_youth, input#total_children, input#total_amount_due').removeAttr('disabled');
	
		return true;
		
	}
}

// Remove event from reunion
function deleteDupe(member_id) {
	event.preventDefault();
	
	var removeRow = $('.deleteDupe input[value="' + member_id + '"]').parent().parent();
	
	$.ajax({
	  method: "DELETE",
	  url: "/members_remove/duplicate/" + member_id,
	  data: {'family_member_remove':member_id}
	})
	
	.fail(function() {	
		alert("Fail");
	})
	
	.done(function(data) {
		$(removeRow).addClass('bounceOut').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
			
			toastr.success(data[0]);
			$(removeRow).remove();
			
			$('.duplicatesCol').fadeTo('fast', 0.00);
			$(data[1]).find('.duplicatesCol')
				.addClass('invisible')
				.appendTo('.duplicatesCol')
				.siblings()
				.remove()
				.unwrap();

			setTimeout(function() {
				$('body').find('.duplicatesCol').removeClass('invisible').addClass('fadeIn');
			}, 1000);
			
		});
		
	});
}

// Keep potential duplicate
function keepDupe(member_id) {
	event.preventDefault();
	
	var removeRow = $('.keepDupe input[value="' + member_id + '"]').parent().parent();
	var removeCard = $(removeRow).parent().parent();
	
	$.ajax({
	  method: "POST",
	  url: "/members_keep/duplicate/" + member_id,
	  data: {'family_member_remove':member_id}
	})
	
	.fail(function() {	
		alert("Fail");
	})
	
	.done(function(data) {
		$(removeRow).addClass('bounceOut').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
			
			toastr.success(data[0]);
			$(removeRow).remove();
		
			if(data[1] == 'Remove Card') {
				
				$(removeCard).addClass('bounceOut');
				
				setTimeout(function() {
					$(removeCard).remove();
				}, 1000);
				
			}
			
		});
		
	});
}

// Add individual member to household
function addToHouseHold(dlID, memberID) {
	$.ajax({
	  method: "PUT",
	  url: "/members/" + memberID + "/add_house_hold",
	  data: {'houseMember':memberID, 'reunion_dl':dlID}
	})
	
	.fail(function() {	
		alert("Fail");
	})
	
	.done(function(data) {
		var newData = $(data).find('.houseHoldBlock');
		var currentHHBlock = $('.houseHoldBlock');
		
		$(currentHHBlock).fadeOut(function() {
			$(newData).addClass('hidden');
			$(newData).insertAfter('.familyTreeGroup').fadeIn(function() {
				$(currentHHBlock).remove();				
			});
		});
	});
}

// Remove individual member from household
function removeFromHouseHold(dlID, memberID) {
	event.preventDefault();
	
	$.ajax({
	  method: "DELETE",
	  url: "/members/" + memberID + "/remove_house_hold",
	  data: {'remove_hh':memberID, 'reunion_dl':dlID}
	})
	
	.fail(function() {	
		alert("Fail");
	})
	
	.done(function(data) {
		var newData = $(data).find('.houseHoldBlock');
		var currentHHBlock = $('.houseHoldBlock');
		
		$(currentHHBlock).fadeOut(function() {
			$(newData).addClass('hidden');
			$(newData).insertAfter('.familyTreeGroup').fadeIn(function() {
				$(currentHHBlock).remove();				
			});
		});
	});
}

// Remove event from reunion
function removeReunionEvent(reunion_event_id) {
	event.preventDefault();
	
	$.ajax({
	  method: "DELETE",
	  url: "/reunion_events/" + reunion_event_id,
	  data: {'reunion_event':reunion_event_id}
	})
	
	.fail(function() {	
		alert("Fail");
	})
	
	.done(function(data) {
		var removeEventRow = $("input[name='event_id[]'][value='"+reunion_event_id+"']").parent().parent();
		
		$(removeEventRow).slideUp('slow', function() {
			$(removeEventRow).remove();
		});
	});
}

// Remove committee member from reunion
function removeCommitteeMember(reunion_committee_member_id) {
	event.preventDefault();
	
	$.ajax({
	  method: "DELETE",
	  url: "/reunion_committee_members/" + reunion_committee_member_id,
	  data: {'reunion_committee':reunion_committee_member_id}
	})
	
	.fail(function() {	
		alert("Fail");
	})
	
	.done(function(data) {
		var removeCommitteeMemberRow = $("input[name='committee_member_id[]'][value='"+reunion_committee_member_id+"']").parent().parent();
		
		$(removeCommitteeMemberRow).slideUp('slow', function() {
			$(removeCommitteeMemberRow).remove();
		});
	});
}

// Add individual member to household
function removeRegistrationModal(regID) {
	$.ajax({
	  method: "GET",
	  url: "/delete_registration/" + regID,
	  data: {'registration':regID}
	})
	
	.fail(function() {	
		alert("Fail");
	})
	
	.done(function(data) {
		var deleteModal = $(data);
		
		$(deleteModal).appendTo($('#profilePage')).ready(function() {
			$('.delete_registration').modal('show');
		});

		$('.delete_registration').on('hidden.bs.modal', function (e) {
			$(this).remove();
		});
	});
}

// Remove member from registration
function remove_from_reg(reg_id, remove_ind) {
	$.ajax({
	  method: "DELETE",
	  url: "/remove_reg_member/" + reg_id + "/" + remove_ind,
	  data: {'remove_ind_member':remove_ind, 'reg_id':reg_id}
	})
	
	.fail(function() {	
		alert("Fail");
	})
	
	.done(function(data) {
		var currentShirtsDiv = $('.shirtSizesDiv');
		var newShirtsDiv = $(data).find('.shirtSizesDiv');
		
		$(currentShirtsDiv).fadeOut(function() {
			$(newShirtsDiv).addClass('hidden');
			$(newShirtsDiv).insertAfter(currentShirtsDiv).fadeIn(function() {
				$(currentShirtsDiv).remove();				
				$(newShirtsDiv).removeClass('hidden');				
			});
		});
	});
}

// Remove error and success messages function
function removeMessages() {
	if($(".flashMessage").length > 0) {
		setTimeout(function() {
			$(".flashMessage").fadeOut();
		}, 6000);
	}
	if($(".errorMessage").length > 0) {
		setTimeout(function() {
			$(".errorMessage").fadeOut();
		}, 6000);
	}
}

// Tooltips Initialization
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});

// MDB Lightbox Init
$(function () {
	$("#mdb-lightbox-ui").load("/addons/mdb-lightbox-ui.html");
});