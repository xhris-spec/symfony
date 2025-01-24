import { Component, OnInit, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Champion } from '../models/champion.model';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
})
export class HomeComponent implements OnInit {
  champions: Champion[] = [];
  selectedChampion: string = '';
  selectedRoles: string[] = [];
  loading = false;
  http = inject(HttpClient);

  ngOnInit() {
    this.loading = true;
    this.http
      .get<Champion[]>('http://127.0.0.1:8003/api/{locale}/champions')
      .subscribe((data) => {
        this.champions = data;
        this.loading = false;
      });
  }

  get championsToShow() {
    return this.champions.filter((champion) => {
      return (
        (!this.selectedChampion || champion.name === this.selectedChampion) &&
        (!this.selectedRoles.length ||
          champion.role.some((r) => this.selectedRoles.includes(r)))
      );
    });
  }

  toggleRole(role: string) {
    const index = this.selectedRoles.indexOf(role);
    if (index > -1) {
      this.selectedRoles.splice(index, 1);
    } else {
      this.selectedRoles.push(role);
    }
  }
}
