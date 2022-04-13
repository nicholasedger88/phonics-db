<!DOCTYPE html>
<html lang="en">
<head>
  <title>OPT</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js" integrity="sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <link rel="icon" href="images/icon.png">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600&display=swap');
    body { font-family: Quicksand !important; }
    #overview { display: none; }
    img { width: 20%; position: relative; opacity: 1; }
       

  </style>

</head>
<body>

<?php 
  include("functions.php"); 
  db_connect();
?>

<div class="container-fluid p-5 bg-primary text-white text-left">
  <div class="row">
    <div class="col-sm-10">
      <h1>Online Pinny Time</h1>
      <?php date_modified(); ?>
    </div>
  </div>
</div>

<div class="container mt-5 text-center">
  <div class="row">
    <div class="col-sm-6">
      <h3>Select class</h3>
      <select id="selectClass" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
          <option selected>Select class</option>

          <?php 
            $class = return_class();

            foreach ($class as $key){
              echo "<option value=".$key.">".$key."</option>";
            }                            
        ?>

        </select>
    </div>
    <div class="col-sm-6 selectpupils">
      <h3>Select a pupil</h3>
        <div id="loadPupilData">
          <select id="pupilName" class="pupilName form-select form-select-lg mb-3" aria-label=".form-select-lg example">
          <option selected>Select pupil</option>
          </select>
        </div>
    </div>
  </div>

      <p>
          <select id="setSelect" class="form-select form-select-lg mb-3" aria-label=".form-select-sm example">
          <option selected>Select Set</option>
          <option val="set2_graphemes" set="set2_graphemes">Set 2</option>
          <option val="set3_graphemes" set="set3_graphemes">Set 3</option>
          </select>

          <span class="load_sound_lists">
            <select id="lessonFocus" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
              <option selected>Select sound focus</option>
             </select>
          </span>

          <button type="button" class="start btn btn-success btn-lg">Start new lesson</button></p> 
      </p>
  
</div>

<div id="overview_info" class="container-fluid p-5 bg-secondary text-white text-center">

  <h1>Overview</h1>
  <p>See below a list of recently completed sessions with a breakdown of correctly indentified words and sounds and misconceptions.</p> 

</div>


<div id="class_overview" class="container-fluid p-5 bg-white text-dark" style="display: none;">
  
  <h1>Pinny Time Record</h1>
  <p class="text-center">
  <span class="text-center" id="prevWeek" wn="0">
    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-chevron-double-left" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M8.354 1.646a.5.5 0 0 1 0 .708L2.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
      <path fill-rule="evenodd" d="M12.354 1.646a.5.5 0 0 1 0 .708L6.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
    </svg>
  </span>
  change week
  <span class="text-center" id="nextWeek" wn="0">
    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-chevron-double-right" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M3.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L9.293 8 3.646 2.354a.5.5 0 0 1 0-.708z"/>
      <path fill-rule="evenodd" d="M7.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L13.293 8 7.646 2.354a.5.5 0 0 1 0-.708z"/>
    </svg>
  </span>
  </p>

<div id="loadOverview"></div>

<!-- get the breakdown info from the individual tables -->
<div id="soundOverview" class="container-fluid p-5 bg-secondary text-white">
</div>

</div>


<div id="overview" class="container-fluid p-5 bg-primary text-white">
  <h1>Name</h1>
  <p>Overview</p> 
</div>

<!-- Full screen modal -->
<div id="sqlLoadingData" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
              <span class="sr-only">Loading data...</span>
            </div>
        </div>
    </div>
</div>

</body>
</html>


<script>
$(document).ready(function(){

var anon = 0;

  $(".start").prop('disabled',true);

$("#selectClass").on('change', function(){
        // reset the lesson focus / set focus
        $("#lessonFocus").prop('selectedIndex',0);
        $("#setSelect").prop('selectedIndex',0);

          $("#overview").empty();
          $("#loadOverview").empty();
          $("#soundOverview").empty();

    // anonymouse toggle - still to be implemented
    $("#anonymousToggle").click(function(){
      if(anon == 0){
        anon = 1;
      } else {
        anon = 0;
      }

    $("#loadPupilData").load("go/get_pupils.php?anon="+anon+"&class="+c, function(response, status){
      // ensure that the data is loaded before allowing the user to select a pupil from the list
      if(status == "success"){ 
        $("#sqlLoadingData").fadeOut(1500, function(){
          $("#sqlLoadingData").modal('hide');
        });
      }
    });
});

      // if a class has been selected - enable the anonymizer to still have an impact

      $("#sqlLoadingData").modal('show');

      let weekNo = 0;
          $("#prevWeek").attr("wn", weekNo);
          $("#nextWeek").attr("wn", weekNo);

      var c = $("#selectClass option:selected").text();
      console.log(c+" week no: "+weekNo);

      $("#class_overview").fadeIn(1500); 

      $("#loadOverview").load("go/get_class_info.php?class="+c+"&week="+weekNo, function(){

          $(".pupilRecord").click(function(){
            var id = $(this).attr("pid");
            $("#pupilRecordModal").load("go/get_pupil_session.php?id="+id);
          });
      });


            $("#prevWeek").click(function(){
            c = $("#selectClass option:selected").text();
            var weekNumber = Number($(this).attr("wn"));
            weekNo = parseInt(weekNumber + 7);

            $("#loadOverview").fadeOut(1000, function(){
              $("#loadOverview").load("go/get_class_info.php?class="+c+"&week="+weekNo).fadeIn(1000, function(){
                $(".pupilRecord").click(function(){
                  var id = $(this).attr("pid");
                  $("#pupilRecordModal").load("go/get_pupil_session.php?id="+id);
                    });                    });
              $("#prevWeek").attr("wn", weekNo);
              $("#nextWeek").attr("wn", weekNo);
            });

          });

            $("#nextWeek").click(function(){
              c = $("#selectClass option:selected").text();
              var weekNumber = Number($(this).attr("wn"));
              if(weekNumber != 0){
                
                weekNo = parseInt(weekNumber - 7);

              $("#loadOverview").fadeOut(1000, function(){
                  $("#loadOverview").load("go/get_class_info.php?class="+c+"&week="+weekNo).fadeIn(1000, function(){
                    $(".pupilRecord").click(function(){
                      var id = $(this).attr("pid");
                      $("#pupilRecordModal").load("go/get_pupil_session.php?id="+id);
                    });                    });

                  $("#prevWeek").attr("wn", weekNo);
                  $("#nextWeek").attr("wn", weekNo);
              });            
          }

    });

    
    $("#loadPupilData").load("go/get_pupils.php?anon="+anon+"&class="+c, function(response, status){

              // ensure that the data is loaded before allowing the user to select a pupil from the list
              if(status == "success"){ 
                $("#sqlLoadingData").fadeOut(500, function(){
                  $("#sqlLoadingData").modal('hide');
                });
              }

      $(".pupilName").on('change', function(){
        // reset the lesson focus / set focus
          $("#soundOverview").empty();
          $("#lessonFocus").prop('selectedIndex',0);
          $("#setSelect").prop('selectedIndex',0);

        var pupilID = $(".pupilName option:selected").attr("pID");
        var pupilName = $(".pupilName option:selected").val();

        $("#overview").load("go/get_overview.php",{ 
        pupilName: pupilName,
        pupilID : pupilID,
        className : c,
        set : "set3_graphemes"
      });

      $("#setSelect").on('change', function(){
        var set = $("#setSelect option:selected").attr("set");
        console.log("load "+set);

        $(".load_sound_lists").load("go/get_sound_list.php",{ "set": set  }, function(){
          
          $("#lessonFocus").on('change', function(){

          var focusChange = this.value;

            $("#soundOverview").load("go/get_breakdown.php?set="+set+"&grapheme="+focusChange+"&pid="+pupilID);

          if (focusChange == "Select sound focus"){
            $(".start").prop('disabled',true);
          } else {
            $(".start").prop('disabled',false);
          }
          });  
            
          });

        
      });

        
      console.log(this.value);

        var pupilID = $(".pupilName option:selected").attr("pID");
        console.log("pupil id: "+pupilID);

      $("#overview").fadeIn(1000);

      });
  
    });

  });

  $(".start").click(function(){
    var focus = $("#lessonFocus option:selected").val();
    var pupilID = $(".pupilName option:selected").attr("pID");
    var pupil_name = $("#pupilName option:selected").val();
    var className = $("#selectClass option:selected").val();
    var set = $("#setSelect option:selected").attr("set");

    window.location.href = "go/index.php?setFocus="+set+"&pupilName="+pupil_name+"&graphemeFocus="+focus+"&pupilID="+pupilID+"&className="+className;

  });

});
</script>

