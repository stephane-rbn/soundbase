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

      // likeTrack.php returns the actual like number
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
  request.open('POST', 'script/likeTrack.php');
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.send('track=' + trackId);
}

function deleteTrack(trackId) {

  const request = new XMLHttpRequest();

  request.onreadystatechange = function() {
    if(request.readyState === 4 && request.status === 200) {
      //Delete track from DOM
      document.getElementById('track-container-' + trackId).remove()
    }
  };
  request.open('POST', 'script/deleteTrack.php');
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.send('track=' + trackId);
}

function addListeningToTrack(trackId) {

  const request = new XMLHttpRequest();

    request.onreadystatechange = function() {
      if(request.readyState === 4 && request.status === 200) {
        // Update listening count on page
        const listeningsNumber = document.getElementById('listenings-number-' + trackId);
        listeningsNumber.innerHTML = request.responseText;
      }
    };
    // Add a listening to database
    request.open('POST', 'script/addListeningToTrack.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send('track=' + trackId);
}

function removeTrackFromPlaylist(trackId, playlistId) {
  const request = new XMLHttpRequest();

  const removedTrack = document.getElementById(`playlist-${playlistId}-track-${trackId}`);

  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      removedTrack.remove();
    }
  };

  request.open('POST', 'script/removeTrackFromPlaylist.php');
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  const body = [
    'playlist_id=' + playlistId,
    'track_id=' + trackId,
  ];

  request.send(body.join('&'));
}

function pauseOtherTracks(tracks,playedTrack) {

  // Run through the array of audio tags on the page
  for (trackNumber = 0; trackNumber < tracks.length; trackNumber++) {

    const track = tracks[trackNumber];

    // If this track in the array is not the currently playing track...
    if (trackNumber != playedTrack) {
      // ...Pause it.
      track.pause();
    }
  }
}

function addComment(track, comment, event, post) {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 201) {
        getComments(track, event, post);
      }
    }
  };
  var url = track ? 'track=' + track : event ? 'event=' + event : post ? 'post='+post : '';
  xhr.open('POST', 'script/addComment.php?'+url);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  var body = [
    'comment=' + comment,
  ];
  xhr.send(body.join('&'));
}

function getComments(track, event, post) {
  var url = track ? 'track=' + track : event ? 'event=' + event : post ? 'post=' + post : '';
  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      var comments = JSON.parse(request.responseText);
      displayComments(comments);
    }
  };
  request.open('GET', 'script/listOfComment.php?' + url);
  request.send();
}

function getAuthorOfComment(comment, parent) {
  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      var author = JSON.parse(request.responseText);
      displayComment(comment, author, parent);
    }
  };
  request.open('GET', 'script/auhtorOfComment.php?id='+comment.member);
  request.send();
}

function displayComments(comments) {
  var parent = document.getElementById('comments');
  parent.innerHTML = '';
  for (var i = 0; i < comments.length; i++) {
    getAuthorOfComment(comments[i], parent)
  }
}

function displayComment(comment, author, parent) {
  var html = commentToHTML(comment, author);
  parent.appendChild(html);
}

function commentToHTML(comment, author) {
  var div = document.createElement('div');
  div.style.border = '2px solid';
  div.style.marginTop = '5px';

  var content = document.createElement('p');
  content.innerHTML = " <img src='uploads/member/avatar/" + author.profile_photo_filename + "' height=50 width=50/>" +author.name +  " - " + comment.content;
  div.appendChild(content);

  return div;
}

