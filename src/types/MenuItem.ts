export interface MenuItem {
  title: string;
  url: string;
  icon?: string;
  children?: MenuItem[];
}
