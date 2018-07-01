function displayTextareaLength(maxLength,textarea) {
  const textareaLength = document.getElementById(textarea).value.length;
  const counter = document.getElementById('textarea-counter')
  counter.innerHTML = 'Number of characters entered: ' + textareaLength;

  if (textareaLength > maxLength) {
    counter.style.color = '#FF0000';
  } else {
    counter.style.color = '#000000';
  }
}

function likeTrack(trackId) {

  let likeNumber = document.getElementById('likeNumber-' + trackId)
  const request = new XMLHttpRequest();

  request.onreadystatechange = function() {

    if(request.readyState === 4 && request.status === 201) {

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
    if(request.readyState === 4 && request.status === 201) {
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
      if(request.readyState === 4 && request.status === 201) {
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
    if (request.readyState === 4 && request.status === 201) {
      removedTrack.remove();
    }
  };

  request.open('POST', 'script/removeTrackFromPlaylist.php');
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  const formData = [
    'playlist_id=' + playlistId,
    'track_id=' + trackId,
  ];

  request.send(formData.join('&'));
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

function addComment(contentType, contentId) {

  // Get the comment content on the page
  const comment = document.getElementById('comment-content').value
  const request = new XMLHttpRequest();

  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 201) {
        // When comment in inserted, refresh the list of comments
        getComments(contentType, contentId);
    }
  };

  // Insert comment
  request.open('POST', 'script/addComment.php');
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  const formData = [
    'contentType=' + contentType,
    'contentId=' + contentId,
    'comment=' + comment,
  ];

  request.send(formData.join('&'));
}

function getComments(contentType, contentId) {

  const request = new XMLHttpRequest();

  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      // Get comments as JSON
      const comments = JSON.parse(request.responseText);
      displayComments(comments);
    }
  };

  const queryStrings = [
    'contentType=' + contentType,
    'contentId=' + contentId,
  ];

  request.open('GET', 'script/commentsList.php?' + queryStrings.join('&'));
  request.send();
}

function displayComments(comments) {

  const container = document.getElementById('comments');
  // Erase all comments on the page
  container.innerHTML = '';

  for (let i = 0; i < comments.length; i++) {
    const comment = comments[i];
    // Using callback: when getCommentAuthor() returns author,
    // displayComment() is called
    getCommentAuthor(comment,function(author) {
      displayComment(comment, author, container);
    });
  }
}

function getCommentAuthor(comment, callback) {

  const request = new XMLHttpRequest();

  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      // Get author info as JSON
      const author = JSON.parse(request.responseText);
      // When author var is available, a callback to displayComment() is made
      callback(author);
    }
  };

  // Get info on the author of the comment from database
  request.open('GET', 'script/getCommentAuthor.php?id='+comment.member);
  request.send();
}

function displayComment(comment, author, container) {

  const content = document.createElement('p');
  content.innerHTML = '<img src="uploads/member/avatar/' + author.profile_photo_filename + '">' +author.name +  ' - ' + comment.content;
  container.appendChild(content);
}
