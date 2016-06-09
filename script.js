(function(){
  angular
  .module("app", [])
  .controller("MainCtrl", MainCtrl);

  function MainCtrl($http){
    var vm = this;
    vm.event = {};

    /**************AJAX CALLS**************/
    // TOPIC
    $http.get("topic.json").success(function(data){
      vm.topicData = data;
    });

    // SPEAKER
    $http.get("speaker.json").success(function(data){
      vm.speakerData = data;
    });

    //BUILDING
    $http.get("building.json").success(function(data){
      vm.buildingData = data;
    });

    // BUILDING && ROOM
    $http.get("buildRoom.json").success(function(data){
      vm.buildRoomData = data;
    });

    // EVENT
    requestEvents();

    function requestEvents(){
      $http.get("event.json").success(function(data){
        vm.eventData = data;
      });
    }

    /**************SUBMITTING INFO**************/
    // BULK TITLE
    vm.submitTitle = function(){

      $http({
        method: "POST",
        url: "bulkTitle.php",
        data: {
          bulkTitle: vm.event.title
        },
        headers: {"Content-type": "application/x-www-form-urlencoded"}
      }).success(function(){
        requestEvents();
      });
      vm.event = {};
    };

    // BULK DATE
    vm.submitEventDate = function(){
      $http({
        method: "POST",
        url: "bulkEventDate.php",
        data: {
          bulkEventDate: vm.event.eventDate,
        },
        headers: {"Content-type": "application/x-www-form-urlencoded"}
      }).success(function(){
        requestEvents();
      });
      vm.event = {};
    };

    // BULK SPEAKER
    vm.submitSpeaker = function(){
      $http({
        method: "POST",
        url: "bulkSpeaker.php",
        data: {
          bulkSpeaker: vm.event.speaker.speaker_name,
        },
        headers: {"Content-type": "application/x-www-form-urlencoded"}
      }).success(function(){
        requestEvents();
      });
      vm.event = {};
    };

    // BULK BUILDING AND ROOM
    vm.submitBuildRoom = function(){
      $http({
        method: "POST",
        url: "bulkBuildRoom.php",
        data: {
          bulkBuilding: vm.event.building.building_name,
          bulkRoom: vm.event.room
        },
        headers: {"Content-type": "application/x-www-form-urlencoded"}
      }).success(function(){
        requestEvents();
      });
      vm.event = {};
    };

    // SPECIFIC EVENT ID
    vm.submit = function($index){
      vm.event.eventId = $index + 1;

      $http({
        method: "POST",
        url: "events.php",
        data: {
          eventId: vm.event.eventId,
          title: vm.event.title,
          eventDate: vm.event.eventDate,
          speaker: vm.event.speaker.speaker_name,
          building: vm.event.building.building_name,
          room: vm.event.room
        },
        headers: {"Content-type": "application/x-www-form-urlencoded"}
      }).success(function(){
        requestEvents();
      });
      vm.event = {};
    };
  }
})();
