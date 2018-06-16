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
      let likeNumber = document.getElementsByClassName('likes')[0].firstElementChild;

      if (likeNumber.className === "far fa-heart") {
        likeNumber.className = "fas fa-heart";
      }
      else if (likeNumber.className === "fas fa-heart") {
        likeNumber.className = "far fa-heart";
      }
    }
  };
  request.open('POST', 'script/like.php');
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.send('track=' + trackId);
}
