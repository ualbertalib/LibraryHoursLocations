$(document).ready(function(){

  
  // first, modify display for enabled javascript
  $('#map').css('overflow', 'hidden');
  $('#slide-content .branch .close-box').css('display', 'block');
  $('#slide-content').css({ 'left' : '-560px' });
  $('#slide-content .branch').css('display', 'none');
  $('#slide-content h2').css('clear', 'none');
  
  
  // set array for the div content options available (each location) <-- ADD/DELTE HERE TO ADD/DELETE LOCATION
  var divsArray = new Array('asian', 'biomedical', 'davidlam', 'education', 'hamber', 'ikblc', 'library', 'chapman', 'rbsc', 'archives', 'koerner', 'law', 'music', 'okanagan', 'robson', 'stpauls', 'woodward', 'xwi7xwa');
  
  
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
    
    $('#slide-content .branch').css('display', 'none');
    $(divid).css('display', 'block');
    
    // need to hack the height for jScrollPane to work w/toggle description, so animate empty dd when library panel selected 
    if (divid == "#library") {
      $('dl.toggle dd.toggle-item.empty').animate(
        {
        height: 'toggle'
        }, 0, function() {
        $('dl.toggle dd.toggle-item.empty').animate({ height: 'toggle'
          }, 0, function() {
        });
      });//closes animate
    }//closes if
    
    // now initialize the scrollpane
    setTimeout(function() {    
      $('.scrollpane').jScrollPane();
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
      left:'-560px'
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


   // when user clicks on a table item  
  $('.slide-out').click(function(){
    
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
    function newMarker(pos, title) {
      
      var marker = new google.maps.Marker({
        position: pos,
        map: map,
        animation: google.maps.Animation.DROP,
        title: title
      });
   
      return marker;
   
    }//closes function
    
    
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
    var latlngDefault = new google.maps.LatLng(49.26565525013921, -123.25337290763855);
    
    // default map appearance
    var mapOptions = {
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
    
    var latlngAsian = new google.maps.LatLng(49.267148, -123.257625);
    var latlngBiomedical = new google.maps.LatLng(49.260848, -123.125474);
    var latlngDavid = new google.maps.LatLng(49.265797, -123.253850);
    var latlngEducation = new google.maps.LatLng(49.264333, -123.252310);
    var latlngHamber = new google.maps.LatLng(49.245709, -123.127641);
    var latlngIrving = new google.maps.LatLng(49.267611, -123.25204); // includes 4 child locations
    var latlngKoerner = new google.maps.LatLng(49.266843, -123.254461);
    var latlngLaw = new google.maps.LatLng(49.269499, -123.253543);
    var latlngMusic = new google.maps.LatLng(49.267115, -123.256477);
    var latlngOkanagan = new google.maps.LatLng(49.940437, -119.394375);
    var latlngRobson = new google.maps.LatLng(49.282019, -123.120833);
    var latlngWoodward = new google.maps.LatLng(49.264150, -123.247779);
    var latlngSt = new google.maps.LatLng(49.280419, -123.127141);
    var latlngXwi7xwa = new google.maps.LatLng(49.265744, -123.256468);
    
    
    // CUSTOM CONTROLS
    
    var okContainer = document.createElement('DIV');
    var okControl = new customControl(okContainer, 'Reset to UBC Okanagan Campus', 'UBC-O', latlngOkanagan, 16);
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(okContainer);
    
    var offContainer = document.createElement('DIV');
    var offControl = new customControl(offContainer, 'Reset to Off-Campus Vancouver', 'OFF-CAMPUS', latlngBiomedical, 13);
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(offContainer);
    
    var campusContainer = document.createElement('DIV');
    var campusControl = new customControl(campusContainer, 'Reset to UBC Vancouver Campus', 'UBC', latlngDefault, 15);
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(campusContainer);
    
    
    // SET MARKERS  <-- ADD/DELETE HERE TO ADD/DELETE LOCATION
    
    var markersArray = [];
    
    var asian = newMarker(latlngAsian, "Asian Library");
    markersArray.push(asian);
   
    var biomedical = newMarker(latlngBiomedical, "Biomedical Branch Library");
    markersArray.push(biomedical);
    
    var davidlam = newMarker(latlngDavid, "David Lam Management Research Library");
    markersArray.push(davidlam);
    
    var education = newMarker(latlngEducation, "Education Library");
    markersArray.push(education);
    
    var hamber = newMarker(latlngHamber, "Hamber Library");
    markersArray.push(hamber);
    
    var ikblc = newMarker(latlngIrving, "Irving K. Barber Learning Centre");
    markersArray.push(ikblc);
    
    var koerner = newMarker(latlngKoerner, "Koerner Library");
    markersArray.push(koerner);
    
    var law = newMarker(latlngLaw, "Law Library");
    markersArray.push(law);
    
    var music = newMarker(latlngMusic, "Music Library");
    markersArray.push(music);
    
    var okanagan = newMarker(latlngOkanagan, "Okanagan Library");
    markersArray.push(okanagan);
   
    var robson = newMarker(latlngRobson, "Robson Square Library");
    markersArray.push(robson);
    
    var stpauls = newMarker(latlngSt, "St. Paul's Hospital Library");
    markersArray.push(stpauls);
    
    var woodward = newMarker(latlngWoodward, "Woodward Library");
    markersArray.push(woodward);
    
    var xwi7xwa = newMarker(latlngXwi7xwa, "Xwi7xwa Library");
    markersArray.push(xwi7xwa);
    
    
    // SET MARKER LISTENERS
    
    // variables to use in for loop  <-- ADD/DELETE HERE TO ADD/DELETE LOCATION
    var markersCount = markersArray.length;
    var markersSelectedChoices = new Array('asian', 'biomedical', 'davidlam', 'education', 'hamber', 'ikblc', 'koerner', 'law', 'music', 'okanagan', 'robson', 'stpauls', 'woodward', 'xwi7xwa');
    
    for (i = 0; i < markersCount; i++) {
      
      // adds "privacy" to set the temp variable
      (function () {
        
        var temp = markersSelectedChoices[i];
      
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

        });
      
      })();//closes function
      
    }//closes for
    
    
    // TABLE EVENTS <-- ADD/DELETE HERE TO ADD/DELETE LOCATION (BOTH HOVER & CLICK)
    
    $('.asian').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 0);
      }, function() {
      tableMouseout(0); }
    );//closes hover
    
    $('.biomedical').hoverIntent(function() {
      tableMouseover(latlngBiomedical, 13, 1);
      }, function() {
      tableMouseout(1); }
    );//closes hover
    
    $('.davidlam').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 2);
      }, function() {
      tableMouseout(2); }
    );//closes hover
    
    $('.education').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 3);
      }, function() {
      tableMouseout(3); }
    );//closes hover
    
    $('.hamber').hoverIntent(function() {
      tableMouseover(latlngBiomedical, 13, 4);
      }, function() {
      tableMouseout(4); }
    );//closes hover
      
    $('.ikblc').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 5);
      }, function() {
      tableMouseout(5); }
    );//closes hover
    
    $('.library').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 5);
      }, function() {
      tableMouseout(5); }
    );//closes hover
    
    $('.chapman').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 5);
      }, function() {
      tableMouseout(5); }
    );//closes hover
    
    $('.rbsc').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 5);
      }, function() {
      tableMouseout(5); }
    );//closes hover
    
    $('.archives').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 5);
      }, function() {
      tableMouseout(5); }
    );//closes hover
    
    $('.koerner').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 6);
      }, function() {
      tableMouseout(6); }
    );//closes hover
    
    $('.law').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 7);
      }, function() {
      tableMouseout(7); }
    );//closes hover
    
    $('.music').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 8);
      }, function() {
      tableMouseout(8); }
    );//closes hover
    
    $('.okanagan').hoverIntent(function() {
      tableMouseover(latlngOkanagan, 16, 9);
      }, function() {
      tableMouseout(9); }
    );//closes hover
    
    $('.robson').hoverIntent(function() {
      tableMouseover(latlngBiomedical, 13, 10); },
      function() {
      tableMouseout(10); }
    );//closes hover
    
    $('.stpauls').hoverIntent(function() {
      tableMouseover(latlngBiomedical, 13, 11);
      }, function() {
      tableMouseout(11); }
    );//closes hover
    
    $('.woodward').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 12);
      }, function() {
      tableMouseout(12); }
    );//closes hover
    
    $('.xwi7xwa').hoverIntent(function() {
      tableMouseover(latlngDefault, 15, 13);
      }, function() {
      tableMouseout(13); }
    );//closes hover  

		$('.asian').click(function() {
      tableMouseover(latlngDefault, 15, 0);
     });//closes hover
    
    
    $('.biomedical').click(function() {
      tableMouseover(latlngBiomedical, 13, 1);
		});//closes click
    
    $('.davidlam').click(function() {
      tableMouseover(latlngDefault, 15, 2);
    });//closes click
    
    $('.education').click(function() {
      tableMouseover(latlngDefault, 15, 3);
    });//closes click
    
    $('.hamber').click(function() {
      tableMouseover(latlngBiomedical, 13, 4);
    });//closes click
      
    $('.ikblc').click(function() {
      tableMouseover(latlngDefault, 15, 5);
    });//closes click
    
    $('.library').click(function() {
      tableMouseover(latlngDefault, 15, 5);
		});//closes click
    
    $('.chapman').click(function() {
      tableMouseover(latlngDefault, 15, 5);
    });//closes click
    
    $('.rbsc').click(function() {
      tableMouseover(latlngDefault, 15, 5);
    });//closes click
    
    $('.archives').click(function() {
      tableMouseover(latlngDefault, 15, 5);
    });//closes click
    
    $('.koerner').click(function() {
      tableMouseover(latlngDefault, 15, 6);
    });//closes click
    
    $('.law').click(function() {
      tableMouseover(latlngDefault, 15, 7);
    });//closes click
    
    $('.music').click(function() {
      tableMouseover(latlngDefault, 15, 8);
    });//closes click
    
    $('.okanagan').click(function() {
      tableMouseover(latlngOkanagan, 16, 9);
    });//closes click
    
    $('.robson').click(function() {
      tableMouseover(latlngBiomedical, 13, 10); 
		});//closes click
    
    $('.stpauls').click(function() {
      tableMouseover(latlngBiomedical, 13, 11);
    });//closes click
    
    $('.woodward').click(function() {
      tableMouseover(latlngDefault, 15, 12);
    });//closes click
    
    $('.xwi7xwa').click(function() {
      tableMouseover(latlngDefault, 15, 13);
    });//closes click
    
    
    // CLOSE BUTTON EVENT <-- ADD/DELETE HERE TO ADD/DELETE LOCATION (IF OTHER THAN DEFAULT)
    
    $('.return-to-map').click(function() {

      // determine view to reset to
      if (window.location.hash == "#view-biomedical" || window.location.hash == "#view-hamber" || window.location.hash == "#view-robson" || window.location.hash == "#view-stpauls") {
        tableMouseover(latlngBiomedical, 13, -1);
      } else if (window.location.hash == "#view-okanagan"){
        tableMouseover(latlngOkanagan, 16, -1);
      } else {
        tableMouseover(latlngDefault, 15, -1);
      }//closes if-elseif-else
      
      // close the panel  
      slideShut();

    });//closes click

  }//closes function
  
  
  // display the Google map
  initialize();
  
  
  // load a location if it is pre-selected (linked to)
  if (window.location.hash != "") {
    
    // pull location id/class from the hash
    var getlocation = window.location.hash;
    var location = getlocation.substr(6);
    
    // if this location is in the locations array (i.e. a valid div), select and display it
    if ($.inArray(location, divsArray) >= 0) {
      
      contentSwitch('#'+location);
      $('.'+location).addClass('selected');
      $('#slide-content').addClass('open-content');
    
    }//closes if
    
  }//closes if


	// display/hide the scroll bar unless someone has mouse over content area
	$('#slide-content').hover(
		function(){
			$('.jspVerticalBar').show();
	 	},
		function(){
			$('.jspVerticalBar').hide();
	});//closes hover

  
  // add and display the scroll bar if needed when the accordion items (e.g. IKBLC Library floors) are clicked
  $('.toggle-list').click(function() {
    // timeout allows slide animation to complete first
		setTimeout(function() {
      $('.scrollpane').jScrollPane();
      $('.jspVerticalBar').show();
    }, 150); 
  });//closes click
    
    
});//closes jQuery