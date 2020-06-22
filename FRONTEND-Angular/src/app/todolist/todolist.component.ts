import { Component, OnInit } from '@angular/core';
import { DatastorageService } from '../datastorage.service';



@Component({
  selector: 'app-todolist',
  templateUrl: './todolist.component.html',
  styleUrls: ['./todolist.component.css']
})

export class TodolistComponent implements OnInit {

  // Variables
  tasklists: any[] = [];

  constructor(public datastorage: DatastorageService) { }


  /* Methods */
  
  ngOnInit(): void {
    this.getAllTasklists();
  }

  // Gets all
  getAllTasklists() {
    this.datastorage.loadAllUser().subscribe(data => {
      this.tasklists = data;
      //console.log(this.tasklists);
    });
  }

}
