"use strict";

require(['jquery', 'knockout','nav'], function($, ko, nav){

    nav();
    
    var ViewModel = function(){
        var self = this;
        self.title = ko.observable();
        self.desc = ko.observable();

        self.save = function(){
            var data = ko.toJSON({"table" : "entries", "data" : [self.title, self.desc]});

            $.post('/admin/galleryEntry', data, function(res) {
                  console.log(res)
            });
        };
    } 

    ko.applyBindings(new ViewModel());
});
    /*var amps  = {*/
        //title: ko.observable(data.title),
        //desc: ko.observable(data.desc),
        //table: ko.observable(data.table)
    //}

    //var ampsAdminViewModel = function(amps) {
        //mapping.fromJS(amps, {}, this);

        //var self = this;
        //self.save = function() {
            //console.log(self);
        //}
    /*}*/

    /*function ampsAdminViewModel() {*/
        //// Data
        //var self = this;
        //self.tasks = ko.observableArray([]);
        //self.newTaskText = ko.observable();
        //self.incompleteTasks = ko.computed(function() {
            //return ko.utils.arrayFilter(self.tasks(), function(task) { return !task.isDone() });
        //});

        //// Operations
        //self.addTask = function() {
            //self.tasks.push(new Task({ title: this.newTaskText() }));
            //self.newTaskText("");
        //};
        //self.removeTask = function(task) { self.tasks.remove(task) };
        
        //// Load initial state from server, convert it to Task instances, then populate self.tasks
        //$.getJSON("/tasks", function(allData) {
            //var mappedTasks = $.map(allData, function(item) { return new Task(item) });
            //self.tasks(mappedTasks);
        //}); 
        
        //self.save = function() {
            //$.ajax("/tasks", {
                //data: ko.toJSON({ tasks: self.tasks }),
                //type: "post", contentType: "application/json",
                //success: function(result) { alert(result) }
            //});
        //}; 
    //}





