function displayTextareaLength(maxLength) {
  const textareaLength = document.getElementsByTagName('textarea')[0].value.length;
  const counter = document.getElementById('textarea-counter')
  counter.innerHTML = 'Number of characters entered: ' + textareaLength;

  if (textareaLength > maxLength) {
    counter.style.color = '#FF0000';
  } else {
    counter.style.color = '#000000';
  }
}

function likeTrack(trackId) {

  let likeNumber = document.getElementById('like-number-' + trackId)
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
        const listeningsNumber = document.getElementById('listening-number-' + trackId);
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
  const comment = document.getElementById('comment-content')
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
    'comment=' + comment.value,
  ];

  request.send(formData.join('&'));
  // Clean form
  comment.value = '';
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

  // Create a table inside the container
  const table = document.createElement('table');
  table.className = "table";
  const tableBody = document.createElement('tbody');
  table.appendChild(tableBody);
  container.appendChild(table);

  for (let i = 0; i < comments.length; i++) {
    const comment = comments[i];
    // Using callback: when getCommentAuthor() returns author,
    // displayComment() is called
    getCommentAuthor(comment,function(author) {
      displayComment(comment, author, tableBody);
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

function displayComment(comment, author, tableBody) {

  // Create a table row for the comment
  const tr = document.createElement('tr');

  // Create 2 cells
  const tdAuthor = document.createElement('td');
  const tdContent = document.createElement('td');

  tdAuthor.innerHTML = '<img src="uploads/member/avatar/' + author.profile_photo_filename + '"><br>' + author.name;

  // Put the content of the comment inside a paragraph
  const pContent = document.createElement('p');
  pContent.innerHTML = comment.content;
  pContent.className = "alert alert-secondary";
  tdContent.appendChild(pContent);

  // Add cells to the row
  tr.appendChild(tdAuthor);
  tr.appendChild(tdContent);

  // Add row to the table
  tableBody.appendChild(tr);
}
