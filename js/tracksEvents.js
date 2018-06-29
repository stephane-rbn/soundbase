
// Get all <audio> tags on the page
const tracks = document.getElementsByTagName('audio');

// If there is at least one track on the page
if (tracks.length > 0) {

  // Add the EventListeners to each of them
  for (trackNumber = 0; trackNumber < tracks.length; trackNumber++) {
    const track = tracks[trackNumber];
    const nextTrack = tracks[trackNumber + 1];
    const trackId = document.getElementById('audio-track-' + trackNumber).getAttribute('data-track-id')

    // Add a listening
    track.addEventListener('ended', function(){addListeningToTrack(trackId)})

    // If the track is not the latest...
    if (trackNumber < tracks.length - 1) {
      // Play next track
      track.addEventListener('ended', function(){nextTrack.play()})
    }

    // Pause all other tracks
    const currentTrackNumber = trackNumber;
    // This^ is needed otherwise trackNumber below is always = to tracks.length...
    track.addEventListener('play', function(){pauseOtherTracks(tracks,currentTrackNumber)})
  }
}
