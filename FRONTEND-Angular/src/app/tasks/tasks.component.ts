import { Component, OnInit, Output, Input } from '@angular/core';
import { DatastorageService } from '../datastorage.service';

@Component({
  selector: 'app-tasks',
  templateUrl: './tasks.component.html',
  styleUrls: ['./tasks.component.css']
})
export class TasksComponent implements OnInit {

  // variables
  @Input() tasks: any = null;

  constructor(public datastorage: DatastorageService) { }

  ngOnInit(): void {
  }

  updateTasks() {
    this.datastorage.updateTasks(this.tasks);
  }
}
