'use strict';
/*
 * Neste js somente funções sem ligações com outras classes
 */
var generateUniqueId, playAudio;

generateUniqueId = function() {
    var ts = +new Date();
    var tsStr = ts.toString();
    var arr = tsStr.split('');
    var rev = arr.reverse();
    var filtered = rev;
    return filtered.join(''); 
};

playAudio = function(soundUrl)
{
    var audioElement = document.createElement('audio');
    audioElement.setAttribute('src', soundUrl);
    audioElement.setAttribute('autoplay', 'autoplay');
    //audioElement.load()
    $.get();
    audioElement.addEventListener("load", function() {
    audioElement.play();
    });
};