import $ from 'jquery';
import FontFaceObserver from 'fontfaceobserver';

/**
 * Various actions
 * @module common
 */
export default {
  /**
   * Init function
   * @function
   */
  init() {
    this.loadFont();
  },

  /**
   * Listen for fontloading
   */
  loadFont() {
    const font = new FontFaceObserver('Font Family', {});

    font.load().then(() => {
      $('html').addClass('wf-loaded');
    });
  },
};
