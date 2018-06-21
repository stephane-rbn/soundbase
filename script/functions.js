function displayStrLength(maxLength) {
  const str = document.getElementById('textarea').value;
  const num = str.length;
  const count = document.getElementById('count')
  count.innerHTML = 'Number of characters entered :' + num;

  if (num > maxLength) {
    document.getElementById('count').style.color = '#FF0000';
  } else {
    document.getElementById('count').style.color = '#000000';
  }
}

function likeTrack(trackId) {

  let likeNumber = document.getElementById('likeNumber-' + trackId)
  const request = new XMLHttpRequest();

  request.onreadystatechange = function() {

    if(request.readyState === 4 && request.status === 200) {

      // If everything went fine in PHP, update the like number and heart with JS

      // like.php returns the actual like number
      likeNumber.innerHTML = request.responseText;

      // Switch to full or empty heart
      let likeButton = document.getElementById('likes-' + trackId).firstElementChild;

      if (likeButton.className === "far fa-heart") {
        likeButton.className = "fas fa-heart";
      }
      else if (likeButton.className === "fas fa-heart") {
        likeButton.className = "far fa-heart";
      }
    }
  };
  request.open('POST', 'script/like.php');
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.send('track=' + trackId);
}

function deleteTrack(trackId) {

  const request = new XMLHttpRequest();

  request.onreadystatechange = function() {
    if(request.readyState === 4 && request.status === 200) {
      //Delete track from DOM
      document.getElementById('track-container-' + trackId).innerHTML = ''
    }
  };
  request.open('POST', 'script/deleteTrack.php');
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.send('track=' + trackId);
}

function playlistPlay(trackId) {
  // Get all <audio> tags on the page
  const tracks = document.getElementsByTagName('audio');
  // Get the current track
  const track = tracks[trackId];

  // If the track is not the latest...
  if (trackId < tracks.length - 1) {
    const nextTrack = tracks[trackId + 1];

    track.onended = function() {
      // ...play the next track...
      nextTrack.play();
      // ...and call the function again for the next track!
      // (since it's triggrered the first time with onClick)
      playlistPlay(trackId + 1);
    };
  }
}

function addListeningToTrack(trackId) {

  const track = document.getElementById('audio-track-' + trackId)

  track.onended = function() {
    const request = new XMLHttpRequest();

    request.onreadystatechange = function() {
      if(request.readyState === 4 && request.status === 200) {
        // Update listening count
        const listeningsNumber = document.getElementById('listenings-number-' + trackId);
        listeningsNumber.innerHTML = request.responseText;
      }
    };
    request.open('POST', 'script/addListeningToTrack.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send('track=' + trackId);
  };

}
