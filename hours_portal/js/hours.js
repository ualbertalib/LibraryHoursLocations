$(document).ready(function(){

  // force page reload when navigation drop-down menu used
  
  function forcePageReload(url) {
    window.location.assign(url);
    window.location.reload(true);
  }//closes function
  
  $("#hours-nav a").click(function() { 
    var url = $(this).attr('href');
    forcePageReload(url);
  });//closes click

  $("#hours-nav-footer a").click(function() { 
    var url = $(this).attr('href');
    forcePageReload(url);
  });//closes click
  
  // first, modify display for enabled javascript
  $('#map').css('overflow', 'hidden');
  $('#slide-content .branch .close-box').css('display', 'block');
  $('#slide-content').css({ 'left' : '-666px' });
  $('#slide-content .branch').css('display', 'none');
  $('#slide-content h2').css('clear', 'none');
  
  
  // set array for the div content options available (each location) <-- ADD/DELTE HERE TO ADD/DELETE LOCATION
  // This Name should match the database login name.
  //var divsArray = new Array('asian', 'biomedical', 'davidlam', 'education', 'ikblc', 'library', 'chapman', 'rbsc', 'archives', 'koerner', 'law', 'okanagan', 'woodward', 'xwi7xwa');
  var divsArray = new Array('cameron','cameronSD','cameronKC', 'rutherford','rutherfordSS' ,'library', 'brucepeel' ,'jws', 'education', 'law', 
                                'business' , 'stjoe',                                 
                                'bsj', 'augustana', 'bard', 'fsa','ill','chat');
  
  
  // the function to open the slide content
  function slideOpen() {
    
    // open the slide content container
    $('#slide-content').stop().animate(
      {
      left:'0px'
      }, {
      queue:false,
      duration:400,
      complete: function() {
        // now keep the slide content open with a class
        $('#slide-content').addClass('open-content');
      }
    });//closes animate
    
  }//closes function

  
  // the function to switch the displayed content
  function contentSwitch(divid) {
   // alert(" contentSwitch " + divid);
    $('#slide-content .branch').css('display', 'none');
    $(divid).css('display', 'block');
    
    // need to hack the height for jScrollPane to work w/toggle description, so animate empty dd when library panel selected 
    //if (divid == "#library") {
    //  $('dl.toggle dd.toggle-item.empty').animate(
    //    {
    //    height: 'toggle'
    //    }, 0, function() {
    //    $('dl.toggle dd.toggle-item.empty').animate({ height: 'toggle'
    //      }, 0, function() {
    //    });
    //  });//closes animate
    //}//closes if
    
    // now initialize the scrollpane (and prevent horizontal scrolling)
    setTimeout(function() {    
      $('.scrollpane').jScrollPane({contentWidth: '1'});
    }, 20);
    
  }//closes function
  
  
  // the function to close the slide content
  function slideShut() {
    
    // remove open styles
    $('#slide-content').removeClass('open-content');
    $('#slide-content').css('left', '0');
    
    // close the slide content container
    $('#slide-content').stop().animate(
      {
      left:'-666px'
      }, {
      queue:false,
      duration:400,
      complete: function() {
        // now remove highlighting of selected item and change hash to 'all"
        $('.slide-out').removeClass('selected');
        window.location.href = '#all'
      }
    });//closes animate
  
  }//closes function
  
  
  // the function to switch between the table and map views on a small screen
  function switchMap() {
    
    // swap out table for map underneath
    $('#locations-table').toggle();
    
    // determine button text
    var label = $('.switch-map').text();
    
    // replace button text
    if (label.indexOf("Map") >= 0) {
      $('.switch-map').html("<img src='img/list.png' /> Table View");
      $('#map').css({'width' : '100%'});
      $('#api').css({'margin-left' : '-115%'});
    } else if (label.indexOf("Table") >= 0) {
      $('.switch-map').html("<img src='img/maps.png' /> Map View");
      $('#map').css({'width' : '95%'});
      $('#api').css({'margin-left' : '-110%'});
    }//closes if-else if
    
  }//closes function
  
  
  // when user clicks on a table item  
  $('.slide-out').on('click', function(){
    
    if ($('.right-side').hasClass('onscreen') == false) {
      $('.right-side').addClass('onscreen');
      $('.left-side').addClass('offscreen');
    }//closes if
    
    // close the window if user selects the already selected item
    if ($(this).hasClass('selected') == true) {
      slideShut();
      return;
    }//closes if
    
    // otherwise, change the selected item to the clicked item
    $('.slide-out').removeClass('selected');
    $(this).addClass('selected');
    
    // open the content
    slideOpen();
    
    // count the array
    var count = divsArray.length;    

    // run through content options and load the appropriate one
    for (i = 0; i < count; i++) {
      
      // change visible content based on class applied to selected item
      if ($(this).hasClass(divsArray[i]) == true) {
        contentSwitch('#'+divsArray[i]);
      }//closes if
      
    }//closes for
		
  });//closes click
  
  // when user on small device wants to switch between table and map view
  $('.switch-map').click(function(){
    switchMap();
  });//closes click
  
  // when user on small device wants to view locations table 
  $('.show-locations').on('click', function(){
    
    if ($('.right-side').hasClass('onscreen') == true) {
      $('.right-side').removeClass('onscreen');
      $('.left-side').removeClass('offscreen');
    }//closes if
    
    $('.right-side .jspPane').css({'top' : '0'}); 
    
    if ($(this).hasClass('map-view') == true) {
      $('.switch-map').html("<img src='img/list.png' /> Table View");
      $('#locations-table').hide();
      $('#map').css({'width' : '100%'});
      $('#api').css({'margin-left' : '-115%'});
      window.scrollTo(0, 150);
    }//closes if
    
  });//closes click
  
  // creates hours highlighting when clicking the calendar days
	$(".month td").live('click',function(){
    
		var hourType = $(this).attr("class");
    if (!hourType) { hourType = "none"; }
    var today = hourType.indexOf("today");
    
    // trim hour type if additional class of today exists
    if (today > -1) {
      hourType = $.trim(hourType.substring(0, today));
    }//closes if
    
		var selectHourType = ".hours-table dl."+hourType;
    
    // when a corresponding class is found in the left-side hours listing
		if ($(selectHourType).hasClass(hourType)) {
      
			$(selectHourType).css(
				'background-color','#eee' // adds a background that is the same color as the background image
			).animate({
				'background-color':'#ffc' // animate it to glow
			},600
			).animate({
				'background-color':'#eee' // animate it back to the background color
			},300,
        function(){
				  $(selectHourType).css(
				  'background-color',''); // remove the background so that it's back to the background texture
			});
      
		}//closes if
    
	});//closes click
   
   
  // the function to initialize Google maps
  function initialize() {
    
    
    // FUNCTIONS
    
    // the function to create new markers
    //the point1 and point2 variables adjust the location of the marker labels
    function newMarker(pos, title, label) {
      
      var point1 = -4;
      var point2 = 24;
      //move the cameron label further left by increasing the value of  point1
      if (label == "Cameron") {
        point1 = 65;
      }
      
     
   
   /* this google.maps.Marker was added March 5,2014 as the MarkerWithLabel 
    * function below sometimes overlapps the markers on a single point. if that starts to happen again use the google.maps.Marker or try changing 
    * versions of MarkerWithLabel
    var marker2 = new google.maps.Marker({
                position: pos,
                map: map,
                title: title
            });*/
      
      var marker = new MarkerWithLabel({
        position: pos,
        map: map,
        title: title,
        labelContent: label,
        labelAnchor: new google.maps.Point(point1, point2),
        labelClass: "map-label"
      });
      
     
   
      return marker;
   
    }; //closes function
    
    
    // the function to create new custom controls (i.e. the view resets)
     function customControl(container, title, text, latlng, zoom) {

      // containing div styles (shadow effect)
      container.style.margin = '7px 0 0 5px';
      container.style.borderStyle = 'solid';
      container.style.borderWidth = '1px';
      container.style.borderColor = '#81867C';
      container.style.borderTop = 'none';
      container.style.borderLeft = 'none';
      
      // create control div and add styles
      var control = document.createElement('DIV');
      control.style.backgroundColor = 'white';
      control.style.borderStyle = 'solid';
      control.style.borderWidth = '1px';
      control.style.borderColor = '#A6A6A6';
      control.style.cursor = 'pointer';
      control.style.textAlign = 'center';
      control.title = title;
      control.style.fontFamily = 'Arial,sans-serif';
      control.style.fontSize = '11px';
      control.style.fontWeight = 'bold';
      control.style.color = '#6784C7';
      control.style.padding = '5px 6px';
      control.innerHTML = text;
      
      // add control to the containing div
      container.appendChild(control);
  
      // add click event listener that resets to appropriate view
      google.maps.event.addDomListener(control, 'click', function() {
        map.panTo(latlng);
        map.setZoom(zoom);
      });
      
    }//closes function
    
      
    // the function on table mouseover
    function tableMouseover(location, zoomno, arrayno) {
      
			// make sure no other marker is animating
      for (x in markersArray) {
				markersArray[x].setAnimation(null);
			}//closes for
			
      // set the appropriate view
      map.panTo(location);
      map.setZoom(zoomno);
      
      // make the related marker bounce
      if (arrayno != -1) {
        markersArray[arrayno].setAnimation(google.maps.Animation.BOUNCE);
      }//closes if
      
    }//closes function
    
    
    // the function on table mouseout (stop the animation)
    function tableMouseout(arrayno) {
    
      markersArray[arrayno].setAnimation(null);
    
    }//closes function
  
    
    // MAP DETAILS
    
    // default center location
    var latlngDefault = new google.maps.LatLng(53.522898, -113.525575);
    
    // default map appearance
    var mapOptions = {
      scrollwheel: false,
      zoom: 15,
      center: latlngDefault,
      disableDefaultUI: true,
      panControl: false,
      zoomControl: true,
      zoomControlOptions: {
        position: google.maps.ControlPosition.TOP_RIGHT,
        style: google.maps.ZoomControlStyle.SMALL
      },
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    
    // the map object
    var map = new google.maps.Map(document.getElementById("api"), mapOptions);
    
    
    // BRANCH COORDINATES <-- ADD/DELETE HERE TO ADD/DELETE LOCATION
     
  
    var latlngCameron = new google.maps.LatLng(53.52682, -113.52383);
    var latlngRutherford = new google.maps.LatLng(53.52590, -113.52173);
    var latlngJWS = new google.maps.LatLng(53.52059, -113.52302);
    var latlngEducation = new google.maps.LatLng(53.52347,  -113.52260);
    var latlngLaw = new google.maps.LatLng(53.52436, -113.51861);     
    var latlngBSJ = new google.maps.LatLng(53.52177, -113.46897);
    
    var latlngAugustana = new google.maps.LatLng(53.01169, -112.82334);

    var latlngBard = new google.maps.LatLng(53.5162359, -113.4204311);
    
    var latlngBusiness = new google.maps.LatLng(53.52741, -113.52145);
    var latlngStJoe = new google.maps.LatLng(53.52446, -113.52457);
    
    
     
    
    
    // excluded because in HSS library
    //var latlngBrucePeel = new google.maps.LatLng(53.52576, -113.52176);
    
    
    
    
    // CUSTOM CONTROLS
    
    var okContainer = document.createElement('DIV');
    var okControl = new customControl(okContainer, 'Reset to Faculty Saint Jean', 'BSJ', latlngBSJ, 16);
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(okContainer);

    var okContainer = document.createElement('DIV');
    var okControl = new customControl(okContainer, 'Reset to BARD', 'BARD', latlngBard, 16);
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(okContainer);
    
    var offContainer = document.createElement('DIV');
    var offControl = new customControl(offContainer, 'Reset to U of A - Augustana', 'Augustana', latlngAugustana, 17);
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(offContainer);
    
    var campusContainer = document.createElement('DIV');
    var campusControl = new customControl(campusContainer, 'Reset to U of A North', 'North Campus', latlngDefault, 15);
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(campusContainer);
    
    
    // SET MARKERS  <-- ADD/DELETE HERE TO ADD/DELETE LOCATION
    
    var markersArray = [];
    
    var scitech = newMarker(latlngCameron, "Cameron Library", "Cameron");
    markersArray.push(scitech);
   
    var hss = newMarker(latlngRutherford, "Rutherford Library", "Rutherford");
    markersArray.push(hss);
    
    var healthsci = newMarker(latlngJWS, "John W. Scott Health Sciences Library", "Health Sciences");
    markersArray.push(healthsci);
    
    var education = newMarker(latlngEducation, "HT Coutts Education Library", "Education");
    markersArray.push(education);
   
    var law = newMarker(latlngLaw, "J.A. Weir Law Library", "Law");
    markersArray.push(law); 
    
    var bsj = newMarker(latlngBSJ, "Bibliotheque Saint-Jean", "BSJ");
    markersArray.push(bsj);
    
    var augustana = newMarker(latlngAugustana, "Augustana Library", "Augustana");
    markersArray.push(augustana);
        
    var business = newMarker(latlngBusiness, "Winspear Business Library", "Business");
    markersArray.push(business);
    
    var stJoe = newMarker(latlngStJoe, "St. Joseph's College", "St. Joseph's College");
    markersArray.push(stJoe);

    var bard = newMarker(latlngBard, "Book and Record Depository", "BARD");
    markersArray.push(bard);
    
   
    /*
    var okanagan = newMarker(latlngOkanagan, "Okanagan Library", "Okanagan");
    markersArray.push(okanagan);
    
    var woodward = newMarker(latlngWoodward, "Woodward Library", "Woodward");
    markersArray.push(woodward);
    
    var xwi7xwa = newMarker(latlngXwi7xwa, "Xwi7xwa Library", "Xwi7xwa");
    markersArray.push(xwi7xwa);*/
    
    
    // SET MARKER LISTENERS
    
    // variables to use in for loop  <-- ADD/DELETE HERE TO ADD/DELETE LOCATION
    // should also match database
    var markersCount = markersArray.length;
    var markersSelectedChoices = new Array('cameron', 'rutherford', 'jws', 'education', 'law', 'bsj', 'augustana', 'business', 'stjoe', 'bard');
    
    for (i = 0; i < markersCount; i++) {
      
      // adds "privacy" to set the temp variable
      (function () {
        
        var temp = markersSelectedChoices[i];
        
        console.log(markersArray[i]['labelContent'] + ': Internal-> ' + markersArray[i]['internalPosition'] + ': position->' + markersArray[i]['position']);
      
        // marker mouseover event
        
        google.maps.event.addListener(markersArray[i], 'mouseover', function () {
          $('.'+temp).addClass('hover');
        });
        
        // marker mouseout event
        google.maps.event.addListener(markersArray[i], 'mouseout', function () {
          $('.slide-out').removeClass('hover');
        });
        
        // marker click event
        google.maps.event.addListener(markersArray[i], 'click', function () {
          slideOpen();
          contentSwitch('#'+temp);
          window.location.href = '#view-'+temp;
          $('.'+temp).addClass('selected');
          if ($('.right-side').hasClass('onscreen') == false) {
            $('.right-side').addClass('onscreen');
            $('.left-side').addClass('offscreen');
          }//closes if
        });
      
      })();//closes function
      
    }//closes for
    
    
    // TABLE EVENTS <-- ADD/DELETE HERE TO ADD/DELETE LOCATION (BOTH HOVER & CLICK) **UPDATE MARKER NUMBERS HERE AS WELL**
    
   
    $('.cameron').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 0);
      }, function() {
      tableMouseout(0); }
    );//closes hover  - asian
      
    $('.cameronSD').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 0);
      }, function() {
      tableMouseout(0); }
    );//closes hover  - asian
      
    $('.cameronKC').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 0);
      }, function() {
      tableMouseout(0); }
    );//closes hover  - asian
    
    $('.rutherford').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 1);
      }, function() {
      tableMouseout(1); }
    );//closes hover - biomedical
    
    $('.rutherfordSS').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 1);
      }, function() {
      tableMouseout(1); }
    );//closes hover - biomedical
    
    
     $('.library').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 1);
      }, function() {
      tableMouseout(1); }
    );//closes hover
    
     $('.brucepeel').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 1);
      }, function() {
      tableMouseout(1); }
    );//closes hover - chapman
    
    $('.jws').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 2);
      }, function() {
      tableMouseout(2); }
    );//closes hover - davidlam
    
    $('.education').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 3);
      }, function() {
      tableMouseout(3); }
    );//closes hover
    
    $('.law').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 4);
      }, function() {
      tableMouseout(4); }
    );//closes hover - ikblc
    
   
    
    $('.bsj').hoverIntent(function() {
      tableMouseover(latlngBSJ, 15, 5);
      }, function() {
      tableMouseout(5); }
    );//closes hover - 

   
    
   
    
     $('.augustana').hoverIntent(function() {
      tableMouseover(latlngAugustana, 16, 6);
      }, function() {
      tableMouseout(6); }
    );//closes hover - okanagan
    
     $('.business').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 7);
      }, function() {
      tableMouseout(7); }
    );//closes hover - okanagan
    
    $('.stjoe').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 8);
      }, function() {
      tableMouseout(8); }
    );//closes hover - woodward

     $('.bard').hoverIntent(function() {
      tableMouseover(latlngBard, 15, 5);
      }, function() {
      tableMouseout(9); }
    );
    
   
    
    
     $('.cameron').click(function() {
      tableMouseover(latlngDefault, 15, 0);
     });//closes hover
    
    $('.rutherford').click(function() {
      tableMouseover(latlngDefault, 13, 1);
		});//closes click
                
    $('.rutherfordSS').click(function() {
      tableMouseover(latlngDefault, 13, 5);
		});//closes click                
                
     $('.library').click(function() {
      tableMouseover(latlngDefault, 13, 5);
		});//closes click
    
    $('.brucepeel').click(function() {
      tableMouseover(latlngDefault, 13, 5);
    });//closes click
    
    $('.jws').click(function() {
      tableMouseover(latlngDefault, 15, 2);
    });//closes click
    
    $('.education').click(function() {
      tableMouseover(latlngDefault, 15, 3);
    });//closes click
    
    $('.law').click(function() {
      tableMouseover(latlngDefault, 15, 4);
    });//closes click
    
    
   
    
    $('.bsj').click(function() {
      tableMouseover(latlngBSJ, 15, 5);
    });//closes click

     $('.bard').click(function() {
      tableMouseover(latlngBard, 15, 5);
    });//closes click
    
    $('.augustana').click(function() {
      tableMouseover(latlngAugustana, 15, 6);
    });//closes click
    
    $('.business').click(function() {
      tableMouseover(latlngDefault, 15, 7);
    });//closes click
    
    $('.stjoe').click(function() {
      tableMouseover(latlngDefault, 15, 8);
    });//closes click
   
 
    
    
    // CLOSE BUTTON EVENT <-- ADD/DELETE HERE TO ADD/DELETE LOCATION (IF OTHER THAN DEFAULT)
    
    $('.return-to-map').click(function() {

      // determine view to reset to
      if (window.location.hash == "#view-augustana") {
        tableMouseover(latlngAugustana, 13, -1);
      } else if (window.location.hash == "#view-bsj") {
        tableMouseover(latlngBSJ, 16, -1);
      } else if (window.location.hash == "#view-bard") {
        tableMouseover(latlngBard, 16, -1);
      } else {
        tableMouseover(latlngDefault, 15, -1);
      }//closes if-elseif-else
      
      // close the panel  
      slideShut();

    });//closes click

  }//closes initialize function
  
  //alert('test');
  // display the Google map
  initialize();
  
  
  // load a location if it is pre-selected (linked to)
  if (window.location.hash != "") {
    
    // pull location id/class from the hash
    var getlocation = window.location.hash;
    var location = getlocation.substr(6);
    //alert(location);
    // if this location is in the locations array (i.e. a valid div), select and display it
    if ($.inArray(location, divsArray) >= 0) {
      
      contentSwitch('#'+location);
      $('.'+location).addClass('selected');
      slideOpen();
      if ($('.right-side').hasClass('onscreen') == false) {
        $('.right-side').addClass('onscreen');
        $('.left-side').addClass('offscreen');
      }//closes if
    
    }//closes if
    
  }//closes if
  
  
  // when user on small device hits back button to original URL
  $(window).hashchange(function(){
    
    var currentURL = window.location.hash;
    if (currentURL == "") {
      window.location.reload(true);
    }//closes if
    
  });//closes hashchange


	// display/hide the scroll bar unless someone has mouse over content area
	$('#slide-content').hover(
		function(){
			$('.jspVerticalBar').show();
	 	},
		function(){
			$('.jspVerticalBar').hide();
	});//closes hover

  
  // add and display the scroll bar if needed when the accordion items (e.g. IKBLC Library floors) are clicked
  //$('.toggle-list').click(function() {
  //  // timeout allows slide animation to complete first
  //  setTimeout(function() {
  //    $('.scrollpane').jScrollPane({contentWidth: '1'});
  //    $('.jspVerticalBar').show();
  //  }, 150); 
  //});//closes click
  
  
});//closes jQuery  
