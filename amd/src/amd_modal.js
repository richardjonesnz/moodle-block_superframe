define(['jquery', 'core/modal_factory'], function($, ModalFactory) {
  var trigger = $('#block_superframe_about');
  ModalFactory.create({
    title: 'About superframe',
    body: '<p>An example from the MoodleBites Developers level 1 course.</p>',
    footer: 'Thank you for your interest',
  }, trigger)
  .done(function(modal) {
    // Do what you want with your new modal.
    modal.close();
  });
});