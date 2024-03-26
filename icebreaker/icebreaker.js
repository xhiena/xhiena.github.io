$(document).ready(function () {
    var participants = [];
    var currentParticipant = null;
    var questions=[];
    // Check URL for participants
    var urlParams = new URLSearchParams(window.location.search);
    questions=getQuestionsfromFile();
    if (urlParams.has('participants')) {
        participantsUrl = urlParams.get('participants');
        if (participantsUrl.length != 0) {
            participants = participantsUrl.split(',');
        }
        updateParticipantsList();
        if (participants.length >= 2) {
            startIcebreaker(); // Automatically start icebreaker with existing participants
        }
    }

    // Add participant
    $('#icebreakerForm').submit(function (e) {
        e.preventDefault();
        var participantName = $('#participantInput').val();
        if (participantName !== '') {
            participants.push(participantName);
            updateParticipantsList();
            updateURLParams();
            $('#participantInput').val('');
            //alert('Participant added successfully!');
            if (participants.length >= 2) {
                startIcebreaker();
            }
        }
    });

    // Update participants list
    function updateParticipantsList() {
        var listItems = participants.map(function (participant) {
            return '<li>' + participant + ' <i class="fa fa-times removeButton" data-name="' + participant + '"></i></li>';
        });
        $('#participantsList').html(listItems.join(''));
    }

    // Event delegation for removing participants
    $('#participantsList').on('click', '.removeButton', function () {
        var participantToRemove = '' + $(this).data('name');
        participants = participants.filter(function (participant) {
            return participant !== participantToRemove;
        });
        updateParticipantsList();
        updateURLParams();
        //alert('Participant removed successfully!');
    });

    // Share participants
    $('#shareButton').click(function () {
        if (participants.length > 0) {
            var participantsText = participants.join(',');
            var shareURL = window.location.origin + window.location.pathname + '?participants=' + encodeURIComponent(participantsText);
            copyToClipboard(shareURL);
            alert('Participants list URL copied to clipboard!');
        } else {
            alert('No participants to share.');
        }
    });

    // Copy text to clipboard
    function copyToClipboard(text) {
        var textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
    }

    // Update URL with participants
    function updateURLParams() {
        var participantsText = participants.join(',');
        var newURL = window.location.origin + window.location.pathname + '?participants=' + encodeURIComponent(participantsText);
        window.history.replaceState({}, '', newURL);
    }
    
    function getQuestionsfromfile(){
        $.get('questions.txt', function (data) {
            var questionsfromfile = data.split('\n').filter(function (question) {
                return question.trim() !== '';
            });
            
            if (questions.length === 0) {
                alert('No questions found.');
                return;
            }
            return questionsfromfile;
        });
    }

    function getQuestion(){
        return questions[Math.floor(Math.random() * questions.length)];
    }
    function getParticipant(){
        return participants[Math.floor(Math.random() * participants.length)];
    }
    // Start icebreaker with existing participants
    function showQuestion(q){
        $('#questionDisplay').text(currentParticipant + ', ' + q);
        $('#questionDisplay h3').text(currentParticipant);
        $('#questionDisplay p').text(q);
    }
    function startIcebreaker() {
        if (participants.length < 2) {
            alert('Please add at least two participants.');
            return;
        }
        var randomParticipant = getParticipant();
        var randomQuestion = getQuestion();
        currentParticipant = randomParticipant;
        showQuestion(randomQuestion);
    }

    // Start icebreaker
    $('#startButton').click(function () {
        startIcebreaker();
    });

    // Ask another question to the same participant
    $('#nextQuestionButton').click(function () {
        if (!currentParticipant) {
            alert('Please start the icebreaker first.');
            return;
        }
        var randomQuestion = getQuestion();
        showQuestion(randomQuestion);
    
    });
});
